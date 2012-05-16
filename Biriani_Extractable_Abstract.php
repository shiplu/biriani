<?php

/**
 * @author Shiplu Mokaddim <shiplu@mokadd.im>
 * @copyright 2012 Shiplu Mokaddim
 * @package Biriani
 */

/**
 * class ExtractableBiriani
 * @property Biriani_Response $response HTTP response
 * @property Biriani_Data $data last extracted data is cached here
 * @property DOMDocument $dom internal DOM manipulation object. Access is protected
 */
abstract class Biriani_Extractable_Abstract implements IExtractable {

    protected $response;
    protected $dom;
    protected $xml;
    public $data;

    public function __construct(Biriani_Response $resp) {
        if ($resp instanceof Biriani_Response) {
            $this->response = $resp;
        }
        $this->data = new Biriani_Data();

        // setting up DOMDocument
        $this->dom = new DOMDocument("1.0", "utf-8");
        $this->dom->recover = true;

        // Disabling dom error and enabling libxml internal error.
        // This is because if any invalid markup found DOM shows 
        // a lot of warning.
        libxml_use_internal_errors(true);
    }

    /**
     * Look for date information in different response headers
     * @param int $default_date if all date fails this will be returned. if you dont specify it time() will be returned.
     * @return int Unix timestamp in UTC
     */
    protected function get_date_from_header($default_date=null) {
        $date = "";
        if ($this->response->get_header("Last-Modified")) {
            $date = strtotime($this->response->get_header("Last-Modified"));
        } elseif ($this->response->get_header("Date")) {
            $date = strtotime($this->response->get_header("Date"));
        } elseif (!is_null($default_date)) {
            $date = $default_date;
        } else {
            // no date information found from any valid places. 
            // using the current time as date
            $date = time();
        }
        return $date;
    }

    public function __destruct() {
        // Reverting back the internal errors flag.
        libxml_use_internal_errors(false);
    }

    /**
     * Cache the data to internal data member and return.
     * Usually its done by wrapping the return value of IExtractable::extract()
     * by this function which follows a return statement
     * @param string $title title of the data
     * @param string $description description of the data
     * @param int $date unix timestamp of date in UTC
     * @param string $link permanent url of the data
     * @return Biriani_Data instance of Biriani_Data after filling with the data
     */

    protected function cache_data($title, $description, $date, $link, $extra=null) {
        // build minimal array to use
        $data = array(
            'title' => $title,
            'description' => $description,
            'link'=>$link,
            'date' => $date
        );
        
        // merge any array data 
        if(!is_null($extra)&&is_array($extra)){
            $data = array_merge($data, $extra);
        }
        
        // now fill the data
        $this->data->fill($data);
        return $this->data;
    }

    /**
     * Load data as XML in the SimpleXML based parser. This parser can be found 
     * in Biriani_Extractable_Abstract::$xml
     */
    protected function load_simplexml() {
        $this->load_dom_xml();
        $this->xml = simplexml_import_dom($this->dom);
        return $this->xml;
    }

    /**
     * Load data as HTML in the DOM based parser. This parser can be found in 
     * Biriani_Extractable_Abstract::$dom
     */
    protected function load_dom_html() {
        $this->dom->loadHTML($this->response->get_content());
        return $this->dom;
    }

    /**
     * Load data as XML in the DOM based parser. This parser can be found in 
     * Biriani_Extractable_Abstract::$dom
     * @param type $options See DOMDocuemnt::loadXML parameters
     */
    protected function load_dom_xml($options=null) {
        $this->dom->loadXML($this->response->get_content(), $options);
        return $this->dom;
    }

}

?>
