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
        $description = (string) $xml->entry[0]->summary;

        $date = "";
        if (isset($xml->entry[0]->updated)) {
            $date = strtotime((string) $xml->entry[0]->updated);
        } else {
            $date = $this->get_date_from_header();
        }

        // parsing link
        $link = "";
        foreach ($xml->entry[0]->link as $link_element) {
            if (isset($link_element->rel) && isset($link_element->type)
                    && (string) $link_element->rel == "alternate"
                    && (string) $link_element->type == "text/html"
                    && isset($link_element->href)) {
                $link = (string) $link_element->href;
                break;
            }
        }

        if (empty($link)) {
            foreach ($xml->link as $link_element) {
                if (isset($link_element->rel) && isset($link_element->type)
                        && (string) $link_element->rel == "alternate"
                        && (string) $link_element->type == "text/html"
                        && isset($link_element->href)) {
                    $link = (string) $link_element->href;
                    break;
                }
            }
        }

        if (empty($link)) {
            // no url found
            // set the request url as link url
            $link = $this->response->get_url();
        }

        return $this->cache_data(array(
                    'title' => $title,
                    'description' => $description,
                    'date' => $date,
                    'link' => $link
                ));
    }

}

// end of FeedBiriani
?>
