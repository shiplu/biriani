<?php

/**
 * Class Biriani_Registry
 * Registers the available extractable service classes
 */
class Biriani_Registry{
    /**
     * Extractable service classes. Class names should be sorted from most
     * preferable to lest preferable.
     * @var array
     */
    public static $services = array(
        'FeedBiriani',
        'TwitterBiriani',
        'WordpressBiriani'
    );
}

?>
