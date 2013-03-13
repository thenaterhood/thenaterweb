<?php

include '../static/core_atom.php';
include '../static/core_blog.php';

$session = new session( array('regen') );
$config = new config();

$feedIsCurrent = checkInventory();
$autoRegen = getConfigOption('auto_feed_regen');
$feedLocation = getConfigOption('dynamic_directory');
$saveFeed = getConfigOption('save_dynamics');

if ( ! $autoRegen && ! $session->regen && file_exists("$feedLocation/feed.xml") ){
	/*
	* If the inventory matches the existing number of items in the
	* directory, return the static feed file
	*/
	include "$feedLocation/feed.xml";
}

else{
	/*
	* If the inventory doesn't match the existing number of items in
	* the directory, regenerate the inventory and the feed file
	* then return the feed file
	*/
	if ( $session->regen || ! $feedIsCurrent || ! file_exists("$feedLocation/feed.xml") ){
		regenInventory();
		$feed = generateFeed();
		$file = fopen("$feedLocation/feed.xml", 'w');
		fwrite($file, $feed->output());
		fclose($file);
	}
	include "$feedLocation/feed.xml";

}


?>
