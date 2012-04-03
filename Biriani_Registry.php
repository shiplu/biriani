<?php

/**
 * @author Shiplu Mokaddim <shiplu@mokadd.im>
 * @copyright 2012 Shiplu Mokaddim
 * @package Biriani
 */

/**
 * Class Biriani_Registry
 * Registers the available extractable service classes
 */
class Biriani_Registry {

    /**
     * Extractable service classes. Class names should be sorted from most
     * preferable to lest preferable.
     * @var array
     */
    public static $services = array(
        'FeedBiriani' => 'FeedBiriani.php',
        'HTMLBiriani' => 'HTMLBiriani.php'
    );

    public static function exists($extractable) {
        return isset(self::$services[$extractable]);
    }

    public static function getfilename($extractable) {
        if (self::exists($extractable)) {
            return self::$services[$extractable];
        } else {
            $message = "Extractable '$extractable' is not found in Registry.";
            throw new BirianiMatchedExtractableNotFoundException($message);
        }
    }

}
?>
