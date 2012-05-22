<?php

/**
 * @author Shiplu Mokaddim <shiplu@mokadd.im>
 * @copyright 2012 Shiplu Mokaddim
 * @package Biriani
 */

/**
 * class Biriani_HTTPTransaction
 */
class Biriani_HTTPTransaction {

    protected $url="";
    protected $headers = array();
    protected $content = "";

    public function get_url() {
        return $this->url;
    }

    public function set_url($url) {
        if (preg_match('#^https?://....#i', $url)) {
            $this->url = $url;
        }
    }

    /**
     * Gets a header
     * @return string the header. If not found an empty string is returned. If no name is supplied all headers are returned
     * @access public
     */
    public function get_headers($name=null) {
        if(is_null($name)){
            return $this->headers;
        }
        $name = strtoupper($name);
        return isset($this->headers[$name]) ? $this->headers[$name] : "";
    }

    /**
     * @param string header set the header
     * @return string
     * @access public
     */
    public function set_header($name, $value) {
        $this->headers[strtoupper($name)] = $value;
    }

    /**
     * @return string
     * @access public
     */
    public function get_content() {
        return $this->content;
    }

    /**
     * @param string content 
     * @return string
     * @access public
     */
    public function set_content($content) {
        $this->content = $content;
    }

    /**
     * Clears up all the headers stored internally
     */
    public function clear_headers() {
        $this->headers = array();
    }

    public function __construct() {
        $this->clear_headers();
        $this->set_content("");
    }

}
?>
