<?php

/**
 * Provides a way to cache object
 *
 * @author shiplu
 */
class Biriani_Cache {

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
        
        if(file_exists($location)&& is_dir($location) 
                && is_writable($location)){
            self::$cache_location = $location;
        }
        
        
    }
    
    /**
     * Creats a hash for the key
     * @param string $key string to make hash of
     * @return string hash of the passed string
     */
    protected static function key_hash($key){
        return hash('sha256', (string)$key);
    }
    
    
    
    /**
     * Sets an object in cache
     * @param string $key name/key of the object 
     * @param mixed $value value of the object. It can be any type
     */
    public static function set($key, $value){
        file_put_contents(self::filename($key), 
                self::box($value), FILE_BINARY);
    }
    

    /**
     * Gets an object in cache
     * @param string $key name/key of the object 
     * @return mixed value of the object. It can be any type
     */
    public static function get($key, $value){
        return self::unbox(file_get_contents(
                self::filename($key), FILE_BINARY));
    }
    
    /** 
     * @param string $key name/key of the object 
     * @return string name of the cache file with full path
     */
    protected static function filename($key){
        return self::$cache_location . DIRECTORY_SEPARATOR .  
                self::hash($key) . ".bc";
    }
    /**
     * Checks if a cache is valid
     * @param string $key name/key of the object 
     * @return bool true if cache is not yet expired
     */
    public static function valid($key){
        return self::filectime(self::filename($key))
                + self::$cache_duration > time();
    }
    
    /**
     * Removes an entry from cache
     * @param string $key name/key of the object 
     */
    public static function remove($key){
        unlink(self::filename($key));
    }
    
    /**
     * boxes or serializes a value
     * @param mixed $value value of the object. It can be any type
     * @return string boxed format of $value
     */
    protected static function box($value){
        return serialize($value);
    }

     /**
     * unboxes or deserializes a string
     * @param string $str boxed formatted string
     * @return mixed value of the object. It can be any type
     */
    protected static function box($str){
        return unserialize($str);
    }
    
}

?>
