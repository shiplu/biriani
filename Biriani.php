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
    
    /**
     * Url of the resource that'll be operating on
     */
    private $url;

    /**
     * After execution this holds the extracted Biriani_Data
     * @var Biriani_Data
     */
    private $data;

    /**
     * @param string url 
     */
    protected function set_url($url) {
        $this->url = $url;
    }


    /**
     * @return string
     */
    public function get_url() {
        return $this->url;
    }

    /**
     * Parsed data
     * @return Biriani_Data
     */
    public function get_cached_data() {
        return $this->data;
    }

    /**
     * Get the resource and parse the Data from content.
     * @return Biriani_Data
     */
    private function execute() {
        
        $resp = null;
        if (Biriani_Cache::valid($this->url)) {
            /* @var $resp Biriani_Response */
            $resp = Biriani_Cache::get($this->url);
        } else {
            // invoke request object;
            $req = new Biriani_Request($this->url);
            /* @var $resp Biriani_Response */
            $resp = $req->run();
            // save to cache
            Biriani_Cache::set($this->url, $resp);
        }
        // Now we got response
        // lets get the data and save it.
        $this->data = $this->extract_data($resp);
        return $this->data;
    }

    /**
     * Gets the data from response.
     * @param Biriani_Response $response response to parse
     * @return IData
     */
    private function extract_data(Biriani_Response $response) {
        /* @var $extractor IExtractable */
        $extractor = $this->get_extractor($response);
        return $extractor->extract();
    }

    /**
     * @param Biriani::Biriani_Response response Response object
     * @return Biriani::IExtractable
     */
    private function get_extractor(Biriani_Response $response) {

        // determining which service can extract data
        $class = null;
        /* @var $biriani IExtractable */
        foreach (array_keys(Biriani_Registry::$services) as $biriani) {
            $status = call_user_func(array($biriani, 'can_extract'), $response);
            if ($status==true){
                $class = $biriani;
                break;
            }
        }

        // no class found. create exception
        if ($class == null) {
            throw new BirianiMatchedExtractableNotFoundException(
                    "No matching extractable found for supplied url: "
                    . $response->get_url()
                    , 1110);
        }

        // create extractors instance
        Biriani_Log::instance()->debug("Extractor = $class");
        $extractor = new $class($response);
        return $extractor;
    }
    
    /**
	 * Setup Caching
	 * @param int $duration duration in seconds before it get invalidated
	 * @param string $cache_save_path a directory where to cached data will be stored
     */
    public function setup_cache($duration, $cache_save_path = '/tmp'){
        Biriani_Cache::setup($duration, $cache_save_path);
    }
    
    /**
     * All in one interface to get data from a url
     * @param string $url location from where data should be grabbed
     * @return Biriani_Data
     */
    public function extract($url){
        $this->set_url($url);
        return $this->execute();
    }
}

?>