<?php

/**
 * @author Shiplu Mokaddim <shiplu@mokadd.im>
 * @copyright 2012 Shiplu Mokaddim
 * @package Biriani
 */


/**
 * This exception occurs when the Request object is not in completed state.
 * Make sure Biriani_Request is completed before processing.
 */
class BirianiUncompletedRequestObjectException extends Exception {

    public function __construct($message, $code) {
        parent::__construct($message, $code);
    }

    public function __toString() {
        return "{$this->code}: Uncompleted Request Object. {$this->message}";
    }

}

/**
 * Extractables for current Content is not found in the extractables repository.
 * Make sure there is a proper extractable, registered.
 */
class BirianiMatchedExtractableNotFoundException extends Exception {

    public function __construct($message, $code) {
        parent::__construct($message, $code);
    }

    public function __toString() {
        return "{$this->code}: No extractable found. {$this->message}";
    }

}

/**
 * Required PHP extensions to run this library not found.
 */
class BirianiRequiredExtensionNotFoundException extends Exception {

    public function __construct($message, $code) {
        parent::__construct($message, $code);
    }

    public function __toString() {
        return "{$this->code}: Required PHP Extension not found. {$this->message}";
    }

}
?>
