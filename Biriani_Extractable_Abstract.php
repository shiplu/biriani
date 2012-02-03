<?php

/**
 * @author Shiplu Mokaddim <shiplu@mokadd.im>
 * @copyright 2012 Shiplu Mokaddim
 * @package Biriani
 */


/**
 * class ExtractableBiriani
 */
abstract class Biriani_Extractable_Abstract implements IExtractable {
 
    protected $response;
    
    public function __construct(Biriani_Response $resp) {
        if($resp instanceof Biriani_Response){
            $this->response = $resp;
        }
    }
}
?>
