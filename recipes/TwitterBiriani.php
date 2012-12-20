<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TwitterBiriani
 * @author shiplu
 */
class TwitterBiriani extends Biriani_Extractable_Abstract {

    public function extract() {
        $this->load_dom_html();

        $domxpath = new DOMXPath($this->dom);

        $tweets =$domxpath->query("//div[@class=\"content\"]"); 
        $tweet =  $tweets->item(0);

        $statuses = $domxpath->query("//p[@class=\"js-tweet-text\"]", $tweet);
        $status = $statuses->item(0)->textContent;

        $links = $domxpath->query("div/small/a", $tweet);
        $link = $links->item(0);
        $href = $link->getAttribute('href');
        
        if(!preg_match("#^https?://#", $href)){
            $href=  ltrim($href, '/');
            $href="http://twitter.com/".$href;
        }

        $small_tags = $domxpath->query("div/small/a/span", $tweet);
        $small = $small_tags->item(0);

        $date =  $small->getAttribute("data-time");
        
        return $this->cache_data($status, $status, $date, $href);
    }

    public static function can_extract(Biriani_Response $response) {
        $d = new DOMDocument("1.0", "utf-8");
        $d->recover = true;

        // Disabling dom error and enabling libxml internal error.
        // This is because if any invalid markup found DOM shows 
        // a lot of warning.
        libxml_use_internal_errors(true);

        $d->loadHTML($response->get_content());
        $con = $d->getElementsByTagName('body')->item(0)->textContent;

        $protected = preg_match("/protected their tweets/i", $con);
        $screen_name = preg_match('/@([\w\d]+)/', $con, $matches1);
        $stats = preg_match_all('/([0-9,]+)\s+(tweets|followers|following)/i', $con, $matches2);

        return $screen_name && $stats && isset($matches2[1]) && isset($matches2[1][2]) && !$protected;
    }

}

?>
