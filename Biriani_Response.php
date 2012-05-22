<?php

/**
 * @author Shiplu Mokaddim <shiplu@mokadd.im>
 * @copyright 2012 Shiplu Mokaddim
 * @package Biriani
 */

/**
 * class Biriani_Response
 * 
 */
class Biriani_Response extends Biriani_HTTPTransaction {

    /**
     * @access private
     */
    private $status;

    /**
     * @return int
     * @access public
     */
    public function get_status_code() {
        return $this->status;
    }

    protected function set_status_code($status) {
        $this->status = $status;
    }

    public function set_content($content) {
        // checking if its an utf data.
        if (preg_match("#charset\s*=\s*utf-8#i", $this->get_headers('CONTENT-TYPE')))
            $this->content = utf8_decode($content);
        else
            $this->content = $content;
    }

// end of member function get_status_code

    public function __construct($status_code) {
        parent::__construct();
        $this->set_status_code($status_code);
    }

}

?>
