<?php

/**
 * @author Shiplu Mokaddim <shiplu@mokadd.im>
 * @copyright 2012 Shiplu Mokaddim
 * @package Biriani
 */

/**
 * class AtomFeedBiriani
 */
class AtomFeedBiriani extends FeedBiriani {

    public function extract() {
        $xml = $this->load_simplexml();
        $title = (string) $xml->entry[0]->title;

        if(isset($xml->entry[0]->summary)){
            $description = (string) ($xml->entry[0]->summary);
        }else if(isset($xml->entry[0]->description)){
            $description = (string) ($xml->entry[0]->description);
        }else if(isset($xml->entry[0]->content)){
            $description = (string) ($xml->entry[0]->content);
        }else{
            $description = (string) ($xml->entry[0]->title);
        }

        $date = "";
        if (isset($xml->entry[0]->updated)) {
            $date = strtotime((string) $xml->entry[0]->updated);
        } else {
            $date = $this->get_date_from_header();
        }

        // parsing link
        $link = "";
        
        foreach ($xml->entry[0]->link as $link_element) {
            $attribs = $link_element->attributes();
            $attribs = (Object) $attribs;
            if (isset($attribs->rel) && isset($attribs->type)
                    && (string) $attribs->rel == "alternate"
                    && (string) $attribs->type == "text/html"
                    && isset($attribs->href)) {
                $link = (string) $attribs->href;
                break;
            }
        }

        if (empty($link)) {
            foreach ($xml->link as $link_element) {
                $attribs = (Object) $link_element;
                if (isset($attribs->rel) && isset($attribs->type)
                        && (string) $attribs->rel == "alternate"
                        && (string) $attribs->type == "text/html"
                        && isset($attribs->href)) {
                    $link = (string) $attribs->href;
                    break;
                }
            }
        }

        if (empty($link)) {
            // no url found
            // set the request url as link url
            $link = $this->response->get_url();
        }

        return $this->cache_data($title, $description, $date, $link);
    }

}

// end of FeedBiriani
?>
