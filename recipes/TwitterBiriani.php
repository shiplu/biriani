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

        /** @var $status DOMNode */
        $status = $this->dom
                        ->getElementById("timeline")
                        ->getElementsByTagName("li")->item(0)
                        ->getElementsByTagName("span")->item(0);
        // $status contains now first 'span.status-body' 

        $status_body = trim($status
                        ->getElementsByTagName("span")
                        ->item(0)->textContent);

        $link = trim($status
                        ->getElementsByTagName("a")->item(1)
                ->attributes->getNamedItem('href')
                ->nodeValue);

        $date = trim((string) $status
                        ->getElementsByTagName("span")->item(3)
                ->attributes->getNamedItem('data')
                ->nodeValue);


        preg_match("/time:[\"']([^'\"]+)/", $date, $m);

        $date = strtotime($m[1]);

        return $this->cache_data($status_body, $status_body, $date, $link);
    }

    public static function can_extract(Biriani_Response $response) {
        $con = $response->get_content();
        $protected = preg_match("/protected their tweets/i", $con);
        $screen_name = preg_match(
                '<meta content="[^"]+" name="page-user-screen_name" />'
                , $con);
        $rss_url = preg_match(
                '|http://twitter.com/statuses/user_timeline/\d+.rss|'
                , $con);
        return $screen_name && $rss_url && !$protected;
    }

}

?>
