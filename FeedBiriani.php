<?php

/**
 * @author Shiplu Mokaddim <shiplu@mokadd.im>
 * @copyright 2012 Shiplu Mokaddim
 * @package Biriani
 */

/**
 * class FeedBiriani
 * 
 */

require_once 'IExtractable.php';
require_once 'Biriani_Extractable_Abstract.php';

class FeedBiriani extends Biriani_Extractable_Abstract {
    
    public function __construct(Biriani_Response $resp) {
        parent::__construct($resp);
    }
    
    public static function can_extract(Biriani_Response $response) {
        $content_type = $response->get_header('CONTENT-TYPE');
        preg_match('#(\w+/\w+)#', $content_type, $m);
        $content_type = strtolower($m[1]);
        $feed_mime_types = array(
            "text/rss", "text/rdf", "text/atom",
            "application/rss", "application/rdf", "application/atom",
            "application/rss+xml", "application/rdf+xml", "application/atom+xml"
        );

        // return true if proper mime type is found
        return (in_array($content_type, $feed_mime_types));
    }

    public function extract() {
        
    }
} // end of FeedBiriani
?>
