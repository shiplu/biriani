<?php

/**
 * class Biriani_HTTPTransaction
 * 
 */
class Biriani_HTTPTransaction {

    /**
     * 
     * @access protected
     */
    protected $url;

    /**
     * 
     * @access protected
     */
    protected $headers;

    /**
     * 
     * @access protected
     */
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
     * 
     *
     * @return string
     * @access public
     */
    public function get_header($name) {
        return isset($this->headers[$name]) ? $this->headers[$name] : "";
    }

// end of member function get_header

    /**
     * 
     *
     * @param string header set the header
     * @return string
     * @access public
     */
    public function set_header($name, $value) {
        $this->headers[$name] = $value;
    }

// end of member function set_header

    /**
     * 
     *
     * @return string
     * @access public
     */
    public function get_content() {
        return $this->content;
    }

// end of member function get_content

    /**
     * 
     *
     * @param string content 

     * @return string
     * @access public
     */
    public function set_content($content) {
        $this->content = $content;
    }

// end of member function set_content

    /**
     * get all headers in array
     * @return array all heades in a associative array
     */
    public function get_all_headers() {
        return $this->headers;
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
        $this->url = "";
    }

}

// end of Biriani_HTTPTransaction

?>
