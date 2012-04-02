<?php
/**
 * @author Shiplu Mokaddim <shiplu@mokadd.im>
 * @copyright 2012 Shiplu Mokaddim
 * @package BirianiExamples
 */

require_once ("../Biriani.php");

$url = 'http://www.youtube.com/watch?v=u_sbC7Z0Lcg&feature=related';
$title = 'Zindagi Ki Na Tute Ladi - Kranti (1080p HD Song)';
$uploader = 'PremGatha1080p';
$b = new Biriani();
$b->set_url($url);
$b->set_cache_duration(10);
$b->set_cache_location('/tmp');
$b->execute();
$data = $b->fetch_data();
//var_data($data);
?>