<?php

/**
 * @author Shiplu Mokaddim <shiplu@mokadd.im>
 * @copyright 2012 Shiplu Mokaddim
 * @package Biriani
 */

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
        $this->set_header("User-Agent", "Biriani/1.0 (PHP/" . PHP_VERSION . "  php5-curl/{$cv['version']})");
        
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

        $content = curl_exec($ch);
        
        if(curl_errno($ch)){
            throw new Exception(
                    "Curl Exception: ". curl_error($ch),
                    curl_errno($ch)
            );
        }
        
        $this->response->set_content($content);
        $this->response->set_url($this->get_url());
        
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

?>
