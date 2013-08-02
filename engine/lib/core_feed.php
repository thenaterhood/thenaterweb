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
 include_once GNAT_ROOT.'/lib/core_blog.php';
 include_once GNAT_ROOT.'/classes/class_feed.php';
 include_once GNAT_ROOT.'/classes/class_inventory.php';

/**
 * Generates an atom feed and returns it
 * 
 * @return $atom (atom_feed): an instance of the atom_feed class
 * 
 */
function generateFeed( $bloguri, $feedTitle, $feedCatchline, $forceRegen, $postDirectory ){

	
	$inventory = new inventory( $postDirectory, $bloguri );
	$posts = $inventory->getFileList();

	$atom = new feed( $postDirectory, $bloguri );

	if ( ! getConfigOption('auto_feed_regen') && ! $forceRegen && $atom->exists() ){
		/*
		* If the inventory matches the existing number of items in the
		* directory, return the static feed file
		*/
		return $atom;
	}

	else if ( $forceRegen ){
		/*
		* If the inventory doesn't match the existing number of items in
		* the directory, regenerate the inventory and the feed file
		* then return the feed file
		*/
		$inventory->regen();
		$atom->reset( $feedTitle, getConfigOption('site_domain')."/$bloguri", $feedCatchline,  date(DATE_ATOM) );

	}

	else{

		$inventory->update();
		$atom->update();
	}

	return $atom;
}

?>
