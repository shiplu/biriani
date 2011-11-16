<?php

require_once dirname(__FILE__). DIRECTORY_SEPARATOR. 'SimplePie.php';

/**
 * Extract data from a feed
 * Currently relies on SimplePie library. Later it'll be ommited
 * @author shiplu
 */
class FeedBiriani extends  ExtractableBiriani{

	public function extract(){
		$sp = new SimplePie();
		$sp->set_raw_data($this->content);
		$sp->init();
		
		// fetch the first feed item
		/* @var $item SimplePie_Item */
		$item= $sp->get_item(0);
		$this->data['title'] = $item->get_title();
		$this->data['description'] = strip_tags($item->get_description(false));
		return $this->get_data();
	}

	public static function can_extract($url, $content=""){
		// we never check if we can extract a feed.
		// becasue we can always do it.
		// its our preferred method
		return true;
	}
}
?>