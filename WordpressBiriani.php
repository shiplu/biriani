<?php

/**
 * @author Shiplu Mokaddim <shiplu@mokadd.im>
 * @copyright 2012 Shiplu Mokaddim
 * @package Biriani
 */


/**
 * class WordpressBiriani
 * 
 */
class WordpressBiriani extends Biriani_Extractable_Abstract    //WARNING: PHP5 does not support multiple inheritance but there is more than 1 superclass defined in your UML model!
            implements IExtractable
{

    /**
     * 
     *
     * @return 
     * @abstract
     * @access public
     */
    abstract public function extract( );

} // end of WordpressBiriani
?>