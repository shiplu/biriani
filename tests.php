<?php

include 'Biriani.php';

$urls = array(
"http://void0.blogspot.com/feeds/posts/default",
"http://void0.blogspot.com/feeds/posts/default?alt=rss",
"http://www.blogger.com/feeds/35598988/posts/default",
"http://forum.projanmo.com/feed-rss-topic31839.xml",
"http://api.twitter.com/1/statuses/user_timeline.atom?screen_name=shiplu"
);



foreach($urls as $x){
	/* @var $x IExtractableBiriani */
	$x  = Biriani::factory($x);
	echo "Class Name: ". get_class($x). PHP_EOL;
	$data=$x->extract();
	echo "Title: {$data['title']}".PHP_EOL;
	echo "Description: {$data['description']}".PHP_EOL.PHP_EOL;
}