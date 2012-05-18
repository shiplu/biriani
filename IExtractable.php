<?php

/**
 * @author Shiplu Mokaddim <shiplu@mokadd.im>
 * @copyright 2012 Shiplu Mokaddim
 * @package Biriani
 */

/**
 * class IExtractableBiriani
 */
interface IExtractable {

    /**
     * @return array extracted data in an associative array
     * @abstract
     * @access public
     * @return Biriani_Data
     */
    function extract();
    
    /**
     * Checks if any http response can be extracted.
     * @return boolean true if it can extract the provided response certainly
     * @param $response Biriani_Response Http response to check
     */
    static function can_extract(Biriani_Response $response);

}

?>
