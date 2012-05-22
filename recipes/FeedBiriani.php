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
class FeedBiriani extends Biriani_Extractable_Abstract {

    /**
     * Get the class name from response content
     * @param Biriani_Response $resp response content to take from
     * @return string|bool Class name on success. boolean false otherwise
     */
    protected static function get_feed_class_name_from_content(Biriani_Response $resp) {
        if (preg_match("/<rss[^:]/i", $resp->get_content())) {
            return 'RSSFeedBiriani';
        } elseif (preg_match("/<feed[^:]/i", $resp->get_content())) {
            return 'AtomFeedBiriani';
        } else {
            return false;
        }
    }

    /**
     * Get the class name from response header
     * @param Biriani_Response $resp response header to take from
     * @return string|bool Class name on success. boolean false otherwise
     */
    protected static function get_feed_class_name_from_header(Biriani_Response $resp) {
        $content_type = $resp->get_headers('Content-Type');
        if ($content_type) {
            if (strpos(strtolower($content_type), 'application/rss') !== false) {
                return 'RSSFeedBiriani';
            } elseif (strpos(strtolower($content_type), 'text/rss') !== false) {
                return 'RSSFeedBiriani';
            } elseif (strpos(strtolower($content_type), 'text/atom') !== false) {
                return 'AtomFeedBiriani';
            } elseif (strpos(strtolower($content_type), 'application/atom') !== false) {
                return 'AtomFeedBiriani';
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * Get the feed class name from response
     * @param Biriani_Response $resp HTTP Response
     * @return string|bool Class name on success. boolean false otherwise
     */
    protected static function get_feed_class_name(Biriani_Response $resp) {
        if (($class_name = self::get_feed_class_name_from_header($resp)) !== false) {
            return $class_name;
        } elseif (($class_name = self::get_feed_class_name_from_content($resp)) !== false) {
            return $class_name;
        } else {
            return false;
        }
    }

    public static function can_extract(Biriani_Response $response) {
        $class_name = self::get_feed_class_name($response);
        if ($class_name !== false) {
            return true;
        } else {
            return false;
        }
    }

    public function extract() {
        $class_name = self::get_feed_class_name($this->response);
        if ($class_name !== false) {
            $object = new $class_name($this->response);
            return $object->extract();
        } else {
            return $this->cache_data('Unsupported Feed Format', 'Unsupported Feed format provided', time(), $this->response->get_url());
        }
    }

}

// end of FeedBiriani
?>
