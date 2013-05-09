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
* Creates a list of files in the working directory, sorts
* and reverses the list, and returns it.  Intended for working
* with blog posts stored as text files with date-coded filenames
*/
function getPostList(){

	
	# Grabs the post directory configured in the root configuration
	$postDir = getConfigOption('post_directory');

	$avoid = getConfigOption('hidden_files');
	$posts = array();
	$handler = opendir($postDir);
	$i = 0;
	while ($file = readdir($handler)){
	// if file isn't this directory or its parent, or itself, add it to the results
	// We check if it's there already because we're migrating from plaintext to json
	// so there may be duplicates.
	if (  !in_array($file, $avoid) and !in_array($file, $posts) and !in_array(substr($file, 0, -5), $posts) ){
		if ( strpos($file,"json") ){
			$posts[] = substr($file, 0, -5);
		}
		else{
			$posts[] = $file;
		}
			$i++;
	}

}
	
	sort($posts);
	$posts = array_reverse($posts);
	
	return $posts;
}

/**
* Checks the number of files in the current directory and
* compares it to how many are listed in the current inventory.
* If the number doesn't match, it returns False.
*/
function checkInventory(){

	
	if ( ! file_exists($config->dynamic_directory.'/inventory.html') ){
		return False;
	}
	
	$existing = count( getPostList() );

	$inventory = $config->dynamic_directory.'/inventory.html';
	$recorded = count(file($inventory));
	if ( $recorded == $existing ){
		return True;
	}
	
	return False;
	
}

/**
 * Regenerates the blog inventory file
 */
function regenInventory(){

	$postDir = getConfigOption('post_directory');
	$inventory = fopen(getConfigOption('dynamic_directory').'/inventory.html', 'w');
	
	$avoid = getConfigOption('hidden_files');
	$handler = opendir('./');
	
	$posts = getPostList();
	
	foreach( $posts as $input ){
		
		$postData = new article("$postDir/$input");

		$item = '<li>'. $postData->list_item_output() .'</li>'."\n";
		fwrite($inventory, $item);

	}
	
	fclose($inventory);
}

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
function getSuggestions($number, $tag){

		$i = 0;
		while ($i < $number){
			echo RandomLine(getConfigOption('dynamic_directory')."/inventory.html");
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
function getPosts($start, $end){

	$posts = getPostList();
	
	for ($i = $start; $i < count($posts) && $i < $end; $i++){
		$nextpost = new article( getConfigOption('post_directory').'/'.$posts[$i] );
		echo $nextpost->output( 'html' );
		echo "<hr />";
	}
	if (! $start <= 0) echo "<a href='?start=".($start - getConfigOption('posts_per_page') )."&amp;end=".($end - getConfigOption('posts_per_page') )."'>Newer Posts</a>";
	if (! $start <= 0 and count($posts) != $i ) echo ' / ';
	if ( count($posts) != $i ) echo "<a href='?start=".($start + getConfigOption('posts_per_page') )."&amp;end=".($end + getConfigOption('posts_per_page') )."'>  Older Posts</a>  ";

}

?>
