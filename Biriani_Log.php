<?php

/**
 * Logs various information
 *
 * @author shiplu
 */
class Biriani_Log {

    /**
     *
     * @var resource file handle where messages will be written.
     */
    private $fh = null;
    /**
     *
     * @var Biriani_Log static singleton instance of Biriani_Log 
     */
    private static $instance = null;

    /**
     * @param string $file path of the file where log messages will be written to. if not given all log messages will be written to standard error
     * @param string $mode file open mode. See fopen() function for details
     */
    public function __construct($file = null, $mode = "a") {
        if (!is_null($file) && file_exists($file) && is_writable($file)) {
            $this->fh = fopen($file, $mode);
        }
    }

    public function __destruct() {
        if (is_resource($this->fh)) {
            fclose($this->fh);
        }
    }

    private function write($str) {
        fwrite(is_resource($this->fh) ? $this->fh : STDERR, "$str\n", strlen("$str\n"));
    }

    private function curdate() {
        return date(DateTime::ISO8601);
    }

    /**
     * log a debug message
     * @param string $message debug message
     */
    public function debug($message) {
        if (defined('DEBUG') || isset($_SERVER['DEBUG']) || (isset($_ENV['DEBUG']))) {
            $this->write(sprintf("[DEBUG %s] %s", $this->curdate(), $message));
        }
    }

    /**
     * log a warning message
     * @param string $message warning message
     */
    public function warn($message) {
        $this->write(sprintf("[WARN %s] %s", $this->curdate(), $message));
    }

    /**
     * log a notice
     * @param string $message notice
     */
    public function notice($message) {
        $this->write(sprintf("[NOTICE %s] %s", $this->curdate(), $message));
    }

    /**
     * log a info message
     * @param string $message info message
     */
    public function info($message) {
        $this->write(sprintf("[INFO %s] %s", $this->curdate(), $message));
    }

    /**
     * log a error message
     * @param string $message error message
     */
    public function error($message) {
        $this->write(sprintf("[ERROR %s] %s", $this->curdate(), $message));
    }

    /**
     * Singleton method for this class
     * @param string $file path of the file where log messages will be written to. if not given all log messages will be written to standard error
     * @param string $mode file open mode. See fopen() function for details
     * @return Biriani_Log
     */
    public static function instance($file = null, $mode = "a") {
        if (is_null(self::$instance)) {
            self::$instance = new Biriani_Log($file, $mode);
        }
        return self::$instance;
    }

}

?>
