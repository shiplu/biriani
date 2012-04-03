<?php

/**
 * @author Shiplu Mokaddim <shiplu@mokadd.im>
 * @copyright 2012 Shiplu Mokaddim
 * @package Biriani
 */

/**
 * class Biriani
 */
class Biriani {
    
    const BIRIANI_CACHE_PREFIX='BirianiCache';
    const BIRIANI_CACHE_SUFFIX='.bc';

    /**
     * 
     * @access private
     */
    private $cache_duration = 3600;

    /**
     * 
     * @access private
     */
    private $cache_location = "/tmp";

    /**
     * 
     * @access private
     */
    private $url;

    /**
     * 
     * @access private
     * @var Biriani_Data
     */
    private $data;

    /**
     * @param int duration Cache duration
     * @access public
     */
    public function set_cache_duration($duration) {
        $this->cache_duration = $duration;
    }

// end of member function set_cache_duration

    /**
     * @return int
     * @access public
     */
    public function get_cache_duration() {
        return $this->cache_duration;
    }

// end of member function get_cache_duration

    /**
     *
     * @param string location 
     * @access public
     */
    public function set_cache_location($location) {
        if (is_dir($location) && is_writable($location)) {
            $this->cache_location = $location;
        }
    }

// end of member function set_cache_location

    /**
     * 
     *
     * @return string
     * @access public
     */
    public function get_cache_location() {
        return $this->cache_location;
    }

// end of member function get_cache_location

    /**
     * 
     *
     * @param string url 

     * @return 
     * @access public
     */
    public function set_url($url) {
        $this->url = $url;
    }

// end of member function set_url

    /**
     * 
     *
     * @return string
     * @access public
     */
    public function get_url() {
        return $this->url;
    }

// end of member function get_url

    /**
     * Parsed data
     * @return Biriani_Data
     * @access public
     */
    public function fetch_data() {
        return $this->data;
    }

    /**
     * gen cache file name from key
     * @param string $key cache file name from key
     */
    public function get_cache_file_name($key="") {
        if (empty($key))
            $key = $this->url;

        return $this->cache_location . DIRECTORY_SEPARATOR
                . self::BIRIANI_CACHE_PREFIX
                . sha1($key)
                . self::BIRIANI_CACHE_SUFFIX;
    }

    /**
     * checks if any cache is valid in the supplied file
     * @param string $file  file name where cache exists
     */
    public function is_cache_valid($file) {
        if(file_exists($file)){
            return time() - filemtime($file) < $this->get_cache_duration();
        }else{
            return false;
        }
    }

    /**
     * Alias of Biriani::is_cache_valid
     * @see Biriani::is_cache_valid
     */
    public function cache_is_valid($file){
        return $this->is_cache_valid($file);
    }
    /**
     * Gets response from cache
     * @param string $file cache file
     * @return Biriani_Response
     */
    public function get_cached_response($file) {
        return unserialize(file_get_contents($file));
    }

    /**
     * Saves a Response to cache for later use
     * @param Biriani_Response response object
     */
    public function set_cached_response(Biriani_Response $response) {
        file_put_contents($this->get_cache_file_name(), serialize($response));
    }

// end of member function fetch_data

    /**
     * 
     *
     * @return 
     * @access public
     */
    public function execute() {
        $filename = $this->get_cache_file_name($this->url);
        $resp = null;
        if ($this->is_cache_valid($filename)) {
            /* @var $resp Biriani_Response */
            $resp = $this->get_cached_response($filename);
        } else {
            // invoke request object;
            $req = new Biriani_Request($this->url);
            /* @var $resp Biriani_Response */
            $resp = $req->run();
            // save to cache
            $this->set_cached_response($resp);
        }
        // Now we got response
        // lets get the data and save it.
        $this->data = $this->extract_data($resp);
    }

    /**
     * Gets the data from response.
     * @param Biriani_Response $response response to parse
     * @return IData
     */
    public function extract_data(Biriani_Response $response) {
        /* @var $extractor IExtractable */
        $extractor = $this->get_extractor($response);
        return $extractor->extract();
    }

    /**
     * @param Biriani::Biriani_Response response Response object
     * @return Biriani::IExtractable
     * @access public
     */
    public function get_extractor(Biriani_Response $response) {

        // determining which service can extract data
        $class = null;
        /* @var $biriani IExtractable */
        foreach (Biriani_Registry::$services as $biriani => $biriani_file) {

            if ($biriani::can_extract($response)) {
                $class = $biriani;
                break;
            }
        }

        // no class found. create exception
        if ($class == null) {
            throw new BirianiMatchedExtractableNotFoundException(
                    "No matching extractable found for supplied Response: "
                    . substr($response->get_content(), 0, 100)
                    , 1110);
        }

        // create extractors instance
        $extractor = new $class($response);
        return $extractor;
    }

// end of member function create_extractable_biriani_from_response

    /**
     * Clears all the data from cache
     * @access public
     * @return boolean false upon failure
     */
    public function clear_cache() {

        $dh = opendir($this->cache_location);

        // directory can not be opened. returning
        if (!$dh)
            return FALSE;

        while (($file = readdir($dh)) !== false) {
            if (is_file($file)) {
                if (strpos($file, self::BIRIANI_CACHE_PREFIX) === 0) {
                    unlink($file);
                }
            }
        }
        return true;
    }

}


/**
 * Autoload function for Biriani
 * @param type $class class name
 * @return string file path containing class
 */
function biriani_autoload($cname) {

    if(class_exists($cname)){
        return true;
    }else{
        $class_map = array(

            // Core Biriani classes
            "Biriani_Data" => "Biriani_Data.php",
            "Biriani_Extractable_Abstract" => "Biriani_Extractable_Abstract.php",
            "Biriani_HTTPTransaction" => "Biriani_HTTPTransaction.php",
            "Biriani_Registry" => "Biriani_Registry.php",
            "Biriani_Request" => "Biriani_Request.php",
            "Biriani_Response" => "Biriani_Response.php",
            "BirianiUncompletedRequestObjectException" => "Exceptions.php",
            "BirianiMatchedExtractableNotFoundException" => "Exceptions.php",
            "BirianiRequiredExtensionNotFoundException" => "Exceptions.php",
            "IFillableData" => "IFillableData.php",
            "IExtractable" => "IExtractable.php",
            "IData" => "IData.php",
        );
        
        // Add Recipes
        $recipes = array_map('basename', glob(__DIR__.'/recipes/*Biriani.php'));
        foreach($recipes as $recipe){
            $class_map[substr($recipe, 0, -4)] = 'recipes/'. $recipe;
        }
        
        $fname = dirname(__FILE__).DIRECTORY_SEPARATOR. $class_map[$cname];
        
        // including the file
        include $fname;
        return true;
    }
}

spl_autoload_register("biriani_autoload");
?>