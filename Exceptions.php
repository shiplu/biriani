<?php
// Some custom exception

class BirianiUncompletedRequestObjectException extends Exception {

    public function __construct($message, $code) {
        parent::__construct($message, $code);
    }

    public function __toString() {
        return "{$this->code}: Uncompleted Request Object. {$this->message}";
    }

}

class BirianiMatchedExtractableNotFoundException extends Exception {

    public function __construct($message, $code) {
        parent::__construct($message, $code);
    }

    public function __toString() {
        return "{$this->code}: No extractable found. {$this->message}";
    }

}

class BirianiRequiredExtensionNotFoundException extends Exception {

    public function __construct($message, $code) {
        parent::__construct($message, $code);
    }

    public function __toString() {
        return "{$this->code}: Required PHP Extension not found. {$this->message}";
    }

}
?>
