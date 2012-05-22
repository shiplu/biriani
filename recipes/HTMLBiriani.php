<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of HTMLBiriani
 * @author shiplu
 */
class HTMLBiriani extends Biriani_Extractable_Abstract {

    public function extract() {
        $this->load_dom_html();
        
        // parsing the title of the document.
        $title = $this->dom->getElementsByTagName('title')->item(0)->textContent;

        // parse the description 
        $desc = "";

        // parsing the descritpion from meta
        $meta_desc = "";
        foreach ($this->dom->getElementsByTagName('meta') as $meta) {

            if ($meta->hasAttribute("name")
                    && $meta->getAttribute("name") == "description"
                    && $meta->hasAttribute("content")) {

                $meta_desc = $meta->getAttribute("content");
                break;
            }
        }

        $body_desc = "";

        // description from meta tag was not found try to fetch it from
        // h1, h2, h3 and p tag
        if (empty($meta_desc)) {
            foreach (array('h1', 'h2', 'h3', 'p') as $tag) {
                foreach ($this->dom->getElementsByTagName($tag) as $t) {
                    if (trim($t->textContent) != "") {
                        $body_desc = $t->textContent;
                        // description found. 
                        // Break all the loops
                        break 2;
                    }
                }
            }
            if (empty($body_desc)) {
                $desc = "No description found";
            } else {
                $desc = $body_desc;
            }
        } else {
            $desc = $meta_desc;
        }



        return $this->cache_data($title, $desc, $this->get_date_from_header(), 
                $this->response->get_url());
    }

    public static function can_extract(Biriani_Response $response) {
        if (strpos($response->get_headers('CONTENT-TYPE'), 'text/html') !== false) {
            return true;
        } else {
            return false;
        }
    }

}

?>
