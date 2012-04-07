<?php

/**
 * @author Shiplu Mokaddim <shiplu@mokadd.im>
 * @copyright 2012 Shiplu Mokaddim
 * @package Biriani
 */

/**
 * class RSSFeedBiriani
 */
class RSSFeedBiriani extends FeedBiriani {

    public function extract() {
        $xml = $this->load_simplexml();
        if (isset($xml->item[0])) {
            $item = $xml->item[0];
        } elseif (isset($xml->channel->item[0])) {
            $item = $xml->channel->item[0];
        } elseif (isset($xml->channel) && isset($xml->channel->title)) {
            $item = $xml->channel;
        } elseif (isset($xml) && isset($xml->title)) {
            $item = $xml;
        } else {
            throw new BirianiMatchedExtractableNotFoundException(
                    "Failed to parse " . $this->response->get_url(),
                    10
            );
        }
        
        // try DOM interface first if it fails use the simple xml
        $dom_item = dom_import_simplexml($item);

        
        if ($dom_item) {
            $title = $dom_item->getElementsByTagName('title')
                            ->item(0)->textContent;
            $description = $dom_item->getElementsByTagName('description')
                            ->item(0)->textContent;
            $link = $dom_item->getElementsByTagName('link')
                            ->item(0)->textContent;

            $date = "";
            if ($dom_item->getElementsByTagName('pubDate')
                    ->length > 0) {
                $date = strtotime($dom_item->getElementsByTagName('pubDate')
                                ->item(0)->textContent);
            }
        } else {
            $title = (string) $item->title;
            $description = (string) $item->description;
            $link = (string) $item->link;

            $date = "";
            if (isset($item->pubDate)) {
                $date = strtotime((string) $item->pubDate);
            }
        }
        if ($date == "") {
            $date = $this->get_date_from_header();
        }
        return $this->cache_data($title, $description, $date, $link);
    }

}

// end of FeedBiriani
?>
