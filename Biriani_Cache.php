<?php

/**
 * Provides a way to cache object
 *
 * @author shiplu
 */
class Biriani_Cache {
	const BIRIANI_CACHE_PREFIX ='birianicache.';
	const BIRIANI_CACHE_SUFFIX ='.bc';

	protected static $cache_location = '/tmp';
	protected static $cache_duration = 3600;

	/**
	 * Setup the Birian_Cache
	 * @param int $duration duration in seconds before it get invalidated
	 * @param string $location a directory where to cached data will be stored
	 */
	public static function setup($duration, $location='/tmp') {
		if (is_int($duration)) {
			self::$cache_duration = $duration;
		}

		if (file_exists($location) && is_dir($location)
		&& is_writable($location)) {
			self::$cache_location = $location;
		}
	}

	/**
	 * Creats a hash for the key
	 * @param string $key string to make hash of
	 * @return string hash of the passed string
	 */
	protected static function key_hash($key) {
		return hash('sha256', (string) $key);
	}

	/**
	 * Sets an object in cache
	 * @param string $key name/key of the object
	 * @param mixed $value value of the object. It can be any type
	 */
	public static function set($key, $value) {
		file_put_contents(self::filename($key), self::box($value), FILE_BINARY);
	}

	/**
	 * Gets an object in cache
	 * @param string $key name/key of the object
	 * @return mixed value of the object. It can be any type
	 */
	public static function get($key) {
		return self::unbox(file_get_contents(
		self::filename($key), FILE_BINARY));
	}

	/**
	 * @param string $key name/key of the object
	 * @return string name of the cache file with full path
	 */
	protected static function filename($key) {
		return self::$cache_location . DIRECTORY_SEPARATOR
		. self::BIRIANI_CACHE_PREFIX
		. self::key_hash($key)
		. self::BIRIANI_CACHE_SUFFIX;
	}

	/**
	 * Checks if a cache is valid
	 * @param string $key name/key of the object
	 * @return bool true if cache is not yet expired
	 */
	public static function valid($key) {

		if(file_exists(self::filename($key)) && is_readable(self::filename($key))) {
			return filectime(self::filename($key))
			+ self::$cache_duration > time();
		}else{
			return false;
		}
	}

	/**
	 * Removes an entry from cache
	 * @param string $key name/key of the object
	 */
	public static function remove($key) {
		unlink(self::filename($key));
	}

	/**
	 * boxes or serializes a value
	 * @param mixed $value value of the object. It can be any type
	 * @return string boxed format of $value
	 */
	protected static function box($value) {
		return serialize($value);
	}

	/**
	 * unboxes or deserializes a string
	 * @param string $str boxed formatted string
	 * @return mixed value of the object. It can be any type
	 */
	protected static function unbox($str) {
		return unserialize($str);
	}

	/**
	 * Clears all the data from cache
	 * @return boolean false upon failure
	 */
	public static function clear() {

		$dh = opendir(self::$cache_location);

		// directory can not be opened. returning
		if (!$dh){
			return FALSE;
		}

		// reading all the biriani cache files and delete one by one
		while (($file = readdir($dh)) !== false) {
			if (is_file($file) &&
			strpos($file, self::BIRIANI_CACHE_PREFIX) === 0){
				unlink($file);
			}
		}
		return true;
	}

}

?>
