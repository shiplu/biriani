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
     * @access private
     */
    private $url;

    /**
     * After execution this holds the extracted Biriani_Data
     * @access private
     * @var Biriani_Data
     */
    private $data;

    /**
     * @param string url 
     * @access public
     */
    public function set_url($url) {
        $this->url = $url;
    }


    /**
     * @return string
     * @access public
     */
    public function get_url() {
        return $this->url;
    }

    /**
     * Parsed data
     * @return Biriani_Data
     * @access public
     */
    public function fetch_data() {
        return $this->data;
    }

    /**
     * Get the resource and parse the Data from content.
     * @return Biriani_Data
     * @access public
     */
    public function execute() {
        
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
                    "No matching extractable found for supplied url: "
                    . $response->get_url()
                    , 1110);
        }

        // create extractors instance
        $extractor = new $class($response);
        return $extractor;
    }
}

?>