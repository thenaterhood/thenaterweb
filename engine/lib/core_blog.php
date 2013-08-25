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
include_once GNAT_ROOT.'/lib/core_web.php';
include_once GNAT_ROOT.'/classes/class_article.php';
include_once GNAT_ROOT.'/classes/class_inventory.php';

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
function getSuggestions($number, $tags, $post_directory){

		$suggestions = array();

		$inventory = new inventory( $post_directory, NULL );

		$pool = $inventory->selectField( 'title' );
		$available = count( $pool );

		$i = 0;
		while ($i < $number && $i < $available ){
			$posts = $inventory->select( 'title', $pool[array_rand($pool)] );
			$post = $posts[0];
			$suggest = '<li><a href="'.htmlentities( $post['link'] ).'">'.$post['title'].'</a></li>';

			if ( ! in_array($suggest, $suggestions) ){
				$suggestions[] = $suggest;
				echo $suggest;
				$i++;
			}
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

function loadBlogConf( $id ){

	$confFile = GNAT_ROOT.'/config/section.d/'.$id.'.conf.xml';
	$conf = array();
	$conf['title'] = "Error";
	$conf['catchline'] = "";
	$conf['commentCode'] = "";


	if ( file_exists($confFile) ){
		$xml = simplexml_load_file( GNAT_ROOT.'/config/section.d/'.$id.'.conf.xml' );
		$conf = $xml[0];
	}



	return $conf;

}

?>
