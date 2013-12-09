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

/**
 * Generates an atom feed and returns it
 * 
 * @return $atom (atom_feed): an instance of the atom_feed class
 * 
 */
function generateFeed( $blogdef, $force_regen ){

	$bloguri = $blogdef->id;
	$feedTitle = $blogdef->title;
	$feedCatchline = $blogdef->catchline;

	$atom = new feed( $feedTitle, $bloguri, $feedCatchline, date(DATE_ATOM) );

	$i = 0;
	$postList = $blogdef->getPostList();

	foreach ($postList as $key => $value) {
		$atom->new_item( $value );
		$i++;
		if ( $i > getConfigOption( 'max_feed_items') )
			break;
	}

	return $atom;
}

?>
