<?php

include '/home/natelev/www/static/core_atom.php';
include '/home/natelev/www/static/core_blog.php';

function generateFeed(){
	/*
	* Generates an atom feed
	* 
	* Arguments:
	*  none
	* Returns:
	*  $atom (atom_feed): an instance of the atom_feed class
	* 
	*/
	
	$posts = getPostList();
	$atom = new atom_feed("The Philosophy of Nate", "http://blog.thenaterhood.com/", "It's the cyber age, stay in the know.", date(DATE_ATOM) );
	
	for ($i = 0; $i < count($posts); $i++){
		
		$newitem = new postObj("entries/$posts[$i]");
		$atom->new_item($newitem);
	}
	return $atom;
}

$regen = setVarFromURL(regen, '', 4);
$feedIsCurrent = checkInventory();
$autoRegen = getConfigOption('auto_feed_regen');
$feedLocation = getConfigOption('dynamic_directory');
$saveFeed = getConfigOption('save_dynamics');

if ( ! $autoRegen && ! $regen && file_exists("$feedLocation/feed.xml") ){
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
	if ( $regen || ! $feedIsCurrent || ! file_exists("$feedLocation/feed.xml") ){
		regenInventory();
		$feed = generateFeed();
		$file = fopen("$feedLocation/feed.xml", 'w');
		fwrite($file, $feed->output());
		fclose($file);
	}
	include "$feedLocation/feed.xml";

}


?>
