<?php

/**
 * @author Shiplu Mokaddim <shiplu@mokadd.im>
 * @copyright 2012 Shiplu Mokaddim
 * @package BirianiExamples
 */
require_once ("../Biriani.php");

$urls = array(
    'http://www.youtube.com/watch?v=u_sbC7Z0Lcg&feature=related',
    'http://stackoverflow.com/questions/9902968/why-does-math-round0-49999999999999994-return-1?newsletter=1&nlcode=47010%7cd7dc',
    'http://twitter.com/shiplu/status/186381254055313408',
    'http://en.wikipedia.org/wiki/List_of_HTTP_headers'
);

foreach ($urls as $url) {
    $b = new Biriani();
    $b->set_url($url);
    $b->set_cache_duration(600);
    $b->set_cache_location('/tmp');
    $b->execute();
    $data = $b->fetch_data();
    
    echo "URL=$url\n";
    echo "\tTitle = " . trim($data->get_title()) . "\n";
    echo "\tDescription = " . trim($data->get_description()) . "\n";
    echo "\tDate = " . gmdate(DATE_ISO8601, $data->get_date()) . "\n";
    $dt = new DateTime("@" . $data->get_date());
    echo $dt->diff(new DateTime("now"))->format("\t%H hours %I minutes %s seconds ago\n");
//echo "On ". $date
}
?>