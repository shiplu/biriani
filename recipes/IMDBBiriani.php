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
}