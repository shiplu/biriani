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
    public $data;
    public function __construct(Biriani_Response $resp) {
        if($resp instanceof Biriani_Response){
            $this->response = $resp;
        }
        $this->data = new Biriani_Data();
        
        // setting up DOMDocument
        $this->dom = new DOMDocument(); 
        $this->dom->recover = true;
        
        // Disabling dom error and enabling libxml internal error.
        // This is because if any invalid markup found DOM shows 
        // a lot of warning.
        libxml_use_internal_errors(true);
    }
    
    public function __destruct(){
        // Reverting back the internal errors flag.
        libxml_use_internal_errors(false);
    }
}
?>
