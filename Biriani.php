<?php

/**
 * class Biriani
 * 
 */
class Biriani {
    const BIRIANI_CACHE_PREFIX='BirianiCache';
    const BIRIANI_CACHE_SUFFIX='.bc';

    /**
     * 
     * @access public
     */
    public $cache_duration = 3600;

    /**
     * 
     * @access public
     */
    public $cache_location = "/tmp";

    /**
     * 
     * @access public
     */
    public $url;

    /**
     * 
     * @access private
     */
    private $data;

    /**
     * @param int duration Cache duration
     * @access public
     */
    public function set_cache_duration($duration) {
        $this->cache_duration = $duration;
    }

// end of member function set_cache_duration

    /**
     * @return int
     * @access public
     */
    public function get_cache_duration() {
        return $this->cache_duration;
    }

// end of member function get_cache_duration

    /**
     *
     * @param string location 
     * @access public
     */
    public function set_cache_location($location) {
        if (is_dir($location) && is_writable($location)) {
            $this->cache_location = $location;
        }
    }

// end of member function set_cache_location

    /**
     * 
     *
     * @return string
     * @access public
     */
    public function get_cache_location() {
        return $this->cache_location;
    }

// end of member function get_cache_location

    /**
     * 
     *
     * @param string url 

     * @return 
     * @access public
     */
    public function set_url($url) {
        $this->url = $url;
    }

// end of member function set_url

    /**
     * 
     *
     * @return string
     * @access public
     */
    public function get_url() {
        return $this->url;
    }

// end of member function get_url

    /**
     * 
     *
     * @return 
     * @access public
     */
    public function fetch_data() {
        return $this->data;
    }

    /**
     * gen cache file name from key
     * @param string $key cache file name from key
     */
    public function get_cache_file_name($key=""){
        if(empty ($key)) $key = $this->url;
        
        return $this->cache_location. DIRECTORY_SEPARATOR 
                .self::BIRIANI_CACHE_PREFIX
                .sha1($key)
                .self::BIRIANI_CACHE_SUFFIX;
    }
    /**
     * checks if any cache is valid in the supplied file
     * @param string $file  file name where cache exists
     */
    public function is_cache_valid($file){
        return time()-filemtime($file) < $this->get_cache_duration();
    }
    
    /**
     * Gets response from cache
     * @param string $file cache file
     * @return Biriani_Response
     */
    public function get_cached_response($file){
        return unserialize(file_get_contents($file));
    }
    
    /**
     * Saves a Response to cache for later use
     * @param Biriani_Response response object
     */
    public function set_cached_response(Biriani_Response $response){
        file_put_contents(serialize($response));
    }
// end of member function fetch_data

    /**
     * 
     *
     * @return 
     * @access public
     */
    public function execute() {
        $filename = $this->get_cache_file_name($this->url);
        $resp = null;
        if($this->cache_is_valid($filename)){
            /* @var $resp Biriani_Response */
            $resp = $this->get_cached_response($filename);
        }else{
            // invoke request object;
            $req = new Biriani_Request($this->url);
            /* @var $resp Biriani_Response */
            $resp = $req->run();
            // save to cache
            $this->set_cached_response($resp);
        }
        // Now we got response
        // lets process it 
    }

// end of member function execute

    /**
     * 
     *
     * @param libBiriani::Biriani_Response response Response object

     * @return libBiriani::ExtractableBiriani
     * @access public
     */
    public function create_extractable_biriani_from_response(Biriani_Response $response) {
        
    }

// end of member function create_extractable_biriani_from_response

    /**
     * Clears all the data from cache
     * @access public
     * @return boolean false upon failure
     */
    public function clear_cache() {

        $dh = opendir($this->cache_location);

        // directory can not be opened. returning
        if (!$dh)
            return FALSE;

        while (($file = readdir($dh)) !== false) {
            if (is_file($file)) {
                if (strpos($file, self::BIRIANI_CACHE_PREFIX) === 0) {
                    unlink($file);
                }
            }
        }
        return true;
    }

}

// end of Biriani

/**
 * class Biriani_HTTPTransaction
 * 
 */
class Biriani_HTTPTransaction {

    /**
     * 
     * @access protected
     */
    protected $url;

    /**
     * 
     * @access protected
     */
    protected $headers;

    /**
     * 
     * @access protected
     */
    protected $content = "";

    public function get_url() {
        return $this->url;
    }

    public function set_url($url) {
        if (preg_match('#^https?://....#i', $url)) {
            $this->url = $url;
        }
    }

    /**
     * 
     *
     * @return string
     * @access public
     */
    public function get_header($name) {
        return isset($this->headers[$name]) ? $this->headers[$name] : "";
    }

// end of member function get_header

    /**
     * 
     *
     * @param string header set the header
     * @return string
     * @access public
     */
    public function set_header($name, $value) {
        $this->headers[$name] = $value;
    }

// end of member function set_header

    /**
     * 
     *
     * @return string
     * @access public
     */
    public function get_content() {
        return $this->content;
    }

// end of member function get_content

    /**
     * 
     *
     * @param string content 

     * @return string
     * @access public
     */
    public function set_content($content) {
        $this->content = $content;
    }

// end of member function set_content

    /**
     * get all headers in array
     * @return array all heades in a associative array
     */
    public function get_all_headers() {
        return $this->headers;
    }

    /**
     * Clears up all the headers stored internally
     */
    public function clear_headers() {
        $this->headers = array();
    }

    public function __construct() {
        $this->clear_headers();
        $this->set_content("");
        $this->url = "";
    }

}

// end of Biriani_HTTPTransaction

/**
 * class Biriani_Request
 * 
 */
class Biriani_Request extends Biriani_HTTPTransaction {

    /**
     * 
     * @access private
     */
    private $request_type;
    const BIRIANI_REQUEST_GET='GET';
    const BIRIANI_REQUEST_POST='POST';
    const BIRIANI_REQUEST_PUT='PUT';
    const BIRIANI_REQUEST_DELETE='DELETE';

    private $request_status;
    const BIRIANI_REQUEST_STATUS_INITIALIZED=0;
    const BIRIANI_REQUEST_STATUS_RUN=1;
    const BIRIANI_REQUEST_STATUS_COMPLETED=2;

    /**
     * Saves Response before returning.
     * @access protected
     * @var Biriani_Response
     */
    protected $response;

    /**
     * @param Biriani_Request_Type request_type 
     * @return 
     * @access public
     */
    public function set_request_type($request_type) {

        // Making sure that proper request type is set
        if (!in_array($request_type, array(self::BIRIANI_REQUEST_DELETE,
                    self::BIRIANI_REQUEST_POST,
                    self::BIRIANI_REQUEST_PUT)
        )) {
            $request_type = self::BIRIANI_REQUEST_GET;
        }

        $this->request_type = $request_type;
    }

    public function set_url($url) {
        if (preg_match('#^https?://....#i', $url)) {
            $this->request_status = self::BIRIANI_REQUEST_STATUS_INITIALIZED;
            $this->url = $url;
        }
    }

// end of member function set_request_type

    /**
     * 
     *
     * @return Biriani_Request_Type
     * @access public
     */
    public function get_request_type() {
        return $this->request_type;
    }

    public function get_request_status() {
        return $this->request_status;
    }

// end of member function get_request_type


    public function __construct($url) {
        parent::__construct();
        $this->set_url($url);
        $this->set_request_type(self::BIRIANI_REQUEST_GET);
        if (!function_exists('curl_init')) {
            throw new BirianiRequiredExtensionNotFoundException("Curl Extension not found", 1);
        }
        // setting up default headers
        $this->set_header('Accept-Encoding', "gzip, deflate");
        $this->set_header("Accept-Charset", "ISO-8859-1,utf-8;q=0.7,*;q=0.7");
        $this->set_header("Accept-language", "*");
        $this->set_header("Accept", ",application/xml"
                . ",application/xhtml+xml"
                . ",application/rss"
                . ",application/rdf"
                . ",application/rdf+xml"
                . ",application/rss+xml"
                . ",application/atom"
                . ",application/atom+xml"
                . ",text/rdf"
                . ",application/javascript"
                . ",text/javascript"
                . ",application/json"
                . ",text/html;q=0.9,text/plain;q=0.8"
        );
        $cv = curl_version();
        $this->set_header("User-Agent", "libBiriani/1.0 (PHP/" . PHP_VERSION . "  php5-curl/{$cv['version']})");
    }

    public function save_headers($ch, $header) {
        $m = array();
        /// Saving http response headers
        if (preg_match('#HTTP/(?P<version>[\d\.]+)\s+(?P<code>\d+)#', $header, $m)) {
            if (isset($m['code'])) {
                $this->responseStatusCode = $m['code'];
                $this->response = new Biriani_Response($m['code']);
            }
        } else if (preg_match('#^(?P<name>[^:]+):\s*(?P<value>.*)#', $header, $m)) {
            if (isset($m['name']) && isset($m['value'])) {
                if ($this->response instanceof Biriani_Response)
                    $this->response->set_header(strtoupper(trim($m['name'])), trim($m['value']));
            }
        }

        return strlen($header);
    }

    /**
     * Runs the HTTP transaction
     * @return Biriani_Response
     */
    public function run() {
        // change the request status
        $this->request_status = self::BIRIANI_REQUEST_STATUS_RUN;
        // Staring curl transaction
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_HEADERFUNCTION, array($this, 'save_headers'));
        curl_setopt($ch, CURLOPT_ENCODING, 1);

        switch ($this->get_request_type()) {
            case self::BIRIANI_REQUEST_POST:
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $this->get_content());
                break;

            case self::BIRIANI_REQUEST_DELETE:
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, self::BIRIANI_REQUEST_DELETE);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $this->get_content());
                break;

            case self::BIRIANI_REQUEST_PUT:
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, self::BIRIANI_REQUEST_PUT);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $this->get_content());
                break;
        }

        $headers = $this->get_all_headers();
        $a_headers = array();
        foreach ($headers as $k => $v)
            $a_headers[] = "$k: $v";

        if (count($a_headers) > 0)
            curl_setopt($ch, CURLOPT_HTTPHEADER, $a_headers);


        $this->response->set_content(curl_exec($ch));

        if (curl_errno($ch) > 0) {
            trigger_error("Curl Error: " . curl_error($ch));
            print_r(curl_getinfo($ch));
        }
        curl_close($ch);

        // mark the status as completed
        $this->request_status = self::BIRIANI_REQUEST_STATUS_COMPLETED;
        
        return $this->response;
    }

}

// end of Biriani_Request

/**
 * class Biriani_Response
 * 
 */
class Biriani_Response extends Biriani_HTTPTransaction {

    /**
     * 
     * @access private
     */
    private $status;

    /**
     * @return int
     * @access public
     */
    public function get_status_code() {
        return $this->status;
    }

    protected function set_status_code($status){
        $this->status = $status;
    }
    public function set_content($content) {
        // checking if its an utf data.
        if (preg_match("#charset\s*=\s*utf-8#", $this->get_header('CONTENT-TYPE')))
            $this->content = utf8_decode($content);
        else
            $this->content = $content;
    }

// end of member function get_status_code

    public function __construct($status_code) {
        parent::__construct();
        $this->set_status_code($status_code);
    }

}

// end of Biriani_Response

/**
 * class IExtractableBiriani
 * 
 */
interface IExtractableBiriani {

    /**
     * 
     *
     * @return 
     * @abstract
     * @access public
     */
    public function extract();
}

// end of IExtractableBiriani

/**
 * class ExtractableBiriani
 * 
 */
abstract class ExtractableBiriani implements IExtractableBiriani {
    
}

// end of ExtractableBiriani
// Some custom exception
class BirianiRequiredExtensionNotFoundException extends Exception {

    public function __construct($message, $code) {
        parent::__construct($message, $code);
    }

    public function __toString() {
        return "{$this->code}: Required PHP Extension not found. {$this->message}";
    }

}

class BirianiUncompletedRequestObjectException extends Exception {

    public function __construct($message, $code) {
        parent::__construct($message, $code);
    }

    public function __toString() {
        return "{$this->code}: Uncompleted Request Object. {$this->message}";
    }

}

?>
