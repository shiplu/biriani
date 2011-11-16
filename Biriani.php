<?php

// user agent to use if any request is sent to web server from Biriani
define('BIRIANI_USER_AGENT', "BirianiBot/popular web resource parser");

/**
 *
 * Class provides Proper web content parser accroding to url
 * @author shiplu
 *
 */
class Biriani{

	/**
	 * Temporary downloaded html content
	 * @var string
	 */
	private $content="";

	/**
	 * Url of the resource. It should be an http url
	 * @var string
	 */
	private $url = "";

	// all type ids
	public  static $extractables = array(
		"Feed"
		
	);


	/**
	 * creates instance of proper IExtractableBiriani
	 * @param string $url url to analyze for creating IExtractableBiriani instance
	 * @return IExtractableBiriani Extractable Biriani
	 */
	public static function factory($url){
		// download the url
		$b = new Biriani($url);
		$w = new WebGet;
		$content = $w->requestContent($url, array(), array(), array("User-Agent"=>BIRIANI_USER_AGENT));
		$content = trim($content);
		$content_type= $w->responseHeaders['CONTENT-TYPE'];

		// check if its a feed
		if (strpos($content_type,'application/rss+xml')!==false
		|| strpos($content_type,'application/rdf+xml')!==false
		|| strpos($content_type,'application/atom+xml')!==false
		|| strpos($content_type,'application/xml')!==false
		|| strpos($content_type,'text/rdf')!==false
		|| strpos($content_type,'text/xml')!==false){

			return new FeedBiriani($url, $content);

		}elseif (strpos($content_type,'text/html')!==false
		|| strpos($content_type,'application/xhtml')!==false
		|| strpos($content_type,'application/xhtml+xml')!==false){
				
			// its not a feed. in that case its an html page at least
			$type = $b->get_resource_type($url);
				
			$class_name=self::$extractables[$type]. "Biriani";
				
			return new $class_name($url, $content);
				
				
		}elseif(in_array($content_type,array('text/plain'))){
				
			return new TextBiriani($url, $content);
				
		}else{
			// this is where I dont want my code goes.
			throw new BirianiNotFoundException($url);
		}

	}

	
	/**
	 * Finds the resource type of a url
	 * @param string $url url to analyze
	 * @param string $content content of the url. if passed will not download content from url;
	 * @return int resource type id
	 */
	public function get_resource_type($url, $content){

		$found = false;
		$services = count(self::$extractables);

		// search all the services and check if they can extract it
		// anyone can save its class to initialize later
		// starting from 2 as first two are Feed and HTML. It'll always match
		for($i = 2; $i<$services; $i++){
			$class_name= self::$extractables[$i];
			if($found = $class_name::can_extract($url)){
				return $i;
			}
		}
			
		// no service match for this content.
		// pass the default HTMl handler
		if(!$found){
			return 0;
		}

	}

	/**
	 * Initialize biriani object with url
	 * @param $url url to analyze
	 */
	private function __construct($url){
		$this->url = $url;
	}
}


/**
 * Subclass of this interface should know how to extract content
 * @author shiplu
 */
interface IExtractableBiriani{

	/**
	 * Extracts data from a given website.
	 * @return array associative array of data extracted.
	 */
	public function extract();

	/**
	 * Checks if the current url can be extracted
	 * @param $url url to analyze
	 * @param string $content content of the url. if passed will not download content from url;
	 * @return bool true if it can parse this url
	 */
	public static function can_extract($url, $content="");

	/**
	 * Initializes Biriani extractables
	 * @param string $url url to take data from;
	 * @param string $content content of the url. if passed will not download content from url;
	 */
	public function __construct($url, $content="");

}


abstract  class ExtractableBiriani implements  IExtractableBiriani{
	protected $url="";
	protected $content="";
	protected $data=array();
	/**
	 * Initializes Biriani extractables
	 * @param string $url url to take data from;
	 * @param string $content content of the url. if passed will not download content from url;
	 */
	public function __construct($url, $content=""){
		if(empty($content)){
			// download content from url
			$w = new WebGet();
			$content = $w->requestContent($url, array(), array(), array('User-Agent'=> BIRIANI_USER_AGENT));
		}
		$this->content = $content;
		$this->url = $url;
	}
	
	/**
	 * gets content
	 */
	public function get_content(){
		return $this->content;
	}
	
	/**
	 * gets url
	 */
	public function get_url(){
		return $this->url;
	}
	
	/**
	 * gets the associative array of data parsed
	 */
	public function get_data(){
		return $this->data;
	}
}

/**
 * If no Service handlers class found this exception is thrown
 * @author shiplu
 *
 */
class BirianiNotFoundException extends Exception{

	protected  $url="";

	public function __construct($url){
		$this->message = "Biriani for $url not found!";
		$this->code = 1;
		$this->url = $url;
	}
	public function __toString(){
		return "[{$this->code}] {$this->message}";
	}
}

// Loading all the services
foreach(Biriani::$extractables as $ex){
	include "Services". DIRECTORY_SEPARATOR . $ex. "Biriani.php" ;
}

// load WebGet
include dirname(__FILE__). DIRECTORY_SEPARATOR. 'WebGet.php';

?>
