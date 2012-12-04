<?php
/**
 * Description of IMDBBiriani
 * @author shiplu
 */
class IMDBBiriani extends HTMLBiriani {
	public function extract(){
		$data = parent::extract();
		// now collect the release date
		$xpath = new DOMXPath($this->dom);
		$times = $xpath->query('//time[@itemprop= "datePublished"]');
		$time = strtotime($times->item(0)->nodeValue);
		$data->set_date($time);
		return $data;
	}
    
    public static function can_extract(Biriani_Response $response) {
        // imdb does not have https url
        preg_match("#^http://www.imdb.com/(title|name|list)/([^/]+)#", $response->get_url(), $m);
        return (is_array($m) && isset($m[1]) && isset($m[2]));
    }
}