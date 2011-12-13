<?php
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
