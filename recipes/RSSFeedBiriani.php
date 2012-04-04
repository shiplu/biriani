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
        $xml = simplexml_load_string($this->response->get_content());
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
        $title = (string) $item->title;
        $description = (string) $item->description;
        $link = (string) $item->link;

        $date = "";
        if (isset($item->pubDate)) {
            $date = strtotime((string) $item->pubDate);
        } else {
            $date = $this->get_date_from_header();
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
