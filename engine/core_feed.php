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
 include 'core_blog.php';
 include 'class_feed.php';
 include_once 'class_inventory.php';

/**
 * Generates an atom feed and returns it
 * 
 * @return $atom (atom_feed): an instance of the atom_feed class
 * 
 */
function generateFeed( $bloguri ){
	
	$inventory = new inventory( getConfigOption('post_directory'), $bloguri );
	$posts = $inventory->getFileList();
	$atom = new feed("The Philosophy of Nate", "http://blog.thenaterhood.com/", "It's the cyber age, stay in the know.", date(DATE_ATOM) );
	
	for ($i = 0; $i < count($posts); $i++){
		$newitem = new article(getConfigOption('post_directory').'/'.$posts[$i], $bloguri );
		$atom->new_item($newitem);
	}
	return $atom;
}

?>
