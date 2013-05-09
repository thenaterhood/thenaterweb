<?php

include '../engine/core_feed.php';
include '../engine/class_inventory.php';

$session = new session( array('regen') );
$config = new config();
$inventory = new inventory( getConfigOption('post_directory') );

$feedIsCurrent = $inventory->current();
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
		$inventory->regen();
		$feed = generateFeed();
		$file = fopen("$feedLocation/feed.xml", 'w');
		fwrite($file, $feed->output( getConfigOption('feed_type') ) );
		fclose($file);
	}
	include "$feedLocation/feed.xml";

}


?>
