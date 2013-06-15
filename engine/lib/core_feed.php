<?php
/**
 * Contains utilities and classes for generating an atom feed. Relies
 * on the article class for retrieving and outputting post data in the
 * feed. Requires existing instances of the config and session classes.
 * 
 * @author Nate Levesque <public@thenaterhood.com>
 * Language: PHP
 * Filename: core_feed.php
 * 
 */

/**
 * Include the main blog functions and classes
 */
 include_once 'core_blog.php';
 include_once '../classes/class_feed.php';
 include_once '../classes/class_inventory.php';

/**
 * Generates an atom feed and returns it
 * 
 * @return $atom (atom_feed): an instance of the atom_feed class
 * 
 */
function generateFeed( $bloguri, $feedTitle, $feedCatchline, $forceRegen, $postDirectory ){

	
	$inventory = new inventory( $postDirectory, $bloguri );
	$posts = $inventory->getFileList();

	$atom = new feed( $bloguri );

	if ( ! getConfigOption('auto_feed_regen') && ! $forceRegen && $atom->exists() ){
		/*
		* If the inventory matches the existing number of items in the
		* directory, return the static feed file
		*/
		return $atom;
	}

	else if ( $forceRegen || ! $atom->exists() ){
		/*
		* If the inventory doesn't match the existing number of items in
		* the directory, regenerate the inventory and the feed file
		* then return the feed file
		*/
		$inventory->regen();
		$atom->reset( $feedTitle, getConfigOption('site_domain')."/$bloguri", $feedCatchline,  date(DATE_ATOM) );

		for ($i = 0; $i < count($posts); $i++){
			$newitem = new article( "$postDirectory/$posts[$i]", $bloguri );
			$atom->new_item($newitem);
		}

		$atom->save();

	}

	else{

		$inventory->update();

		$feedItems = $atom->feedItems();

		$newestItems = array_slice($posts, 0, 200);


		$added = array_diff_key($newestItems, $feedItems);
		$removed = array_diff_key($feedItems, $newestItems);

		
		//foreach ( $removed as $input ){
		//	unset( $inventoryItems[$input] );
		//}

		foreach ($added as $input) {

			$postData = new article("$postDirectory/$input", $this->bloguri );
			$atom->new_item($newitem);
		}

		$atom->save();
	}

	return $atom;
}

?>
