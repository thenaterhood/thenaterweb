<?php
/**
 * Contains classes and functions for retrieving, displaying, and
 * managing blog posts and other aspects of the blog platform
 * 
 * @author Nate Levesque <public@thenaterhood.com>
 * Language: PHP
 * Filename: core_blog.php
 * 
 */

/**
 * Includes the core_web functions
 */
include_once 'core_web.php';
include_once 'class_article.php';
include_once 'class_inventory.php';

/**
* Returns a random line of a given file.
* Used mainly for generating random suggestions 
* for additional blog posts to read.
* 
* @param $filename - a filename to pull a line from
*/
function RandomLine($filename) {

	$lines = file($filename) ;
	return $lines[array_rand($lines)] ;
}

/**
* Generates and displays a list of additional 'suggested' blog
* posts.  Right now picks them randomly, but in the future might
* rely on a better algorithm.
* 
* @param $number (int): how many to generate and display
* @param $tag (string): a tag or tags to use for generating suggestions
*/
function getSuggestions($number, $tag, $post_directory){

		$inventory = new inventory( $post_directory );

		$pool = $inventory->selectField( 'title' );

		$i = 0;
		while ($i < $number){
			$post = $inventory->select( 'title', $pool[array_rand($pool)] );
			print '<li><a href="'.$post[0]->link.'">'.$post[0]->title.'</a></li>';
			$i++;
		}
		
	}

/**
 * Lists the files in a directory and returns an array of them
 * out to the given length section
 * 
 * @param $start (int) - a starting index for the files
 * @param $end (int) - an ending index for the files
 * 
 * @return $posts (array) - an array of post objects
 * 
 */
function getPosts( $bloguri, $post_directory, $start, $end){

	$inventory = new inventory( $post_directory, $bloguri );
	$posts = $inventory->getFileList();
	
	for ($i = $start; $i < count($posts) && $i < $end; $i++){
		$nextpost = new article( $post_directory.'/'.$posts[$i], $bloguri );
		echo $nextpost->output( 'html' );
		echo "<hr />";
	}
	if (! $start <= 0) echo "<a href='?start=".($start - getConfigOption('posts_per_page') )."&amp;end=".($end - getConfigOption('posts_per_page') )."'>Newer Posts</a>";
	if (! $start <= 0 and count($posts) != $i ) echo ' / ';
	if ( count($posts) != $i ) echo "<a href='?start=".($start + getConfigOption('posts_per_page') )."&amp;end=".($end + getConfigOption('posts_per_page') )."'>  Older Posts</a>  ";

}

?>
