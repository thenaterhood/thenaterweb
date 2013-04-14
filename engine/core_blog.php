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
include 'core_web.php';

/**
* Contains everything to do with retrieving and outputting
* posts in multiple forms.  Is capable of retrieving posts stored
* in .json format (preferred when available) as well as plaintext
* (file syntax described below in constructor).
* 
* Contains functions to output the post data in html format
* for displaying to a page, and atom format for use in generating
* an atom feed.
*/
class postObj extends dataMonger{
	
	/**
	 * Reads and parses a post file and creates an instance
	 * of the class with the post data. Capable of managing
	 * posts in json and plaintext, but prefers json if
	 * a json file exists for the requested post.
	 * 
	 * @param nodefile (string) - a yyyy.mm.dd string of a nodefile
	 */
	public function __construct($nodefile){

		/* Handles the case where the post file does not exist
		 * at all by pre-setting all the fields to a failure state.
		 * This also safely handles any case where the data in a post
		 * doesn't contain all of the expected fields in a typical way.
		 */
		
		$this->container['title'] = "Oops! Post Not Found!";
		$this->container['date'] = "";
		$this->container['tags'] = "";
		$this->container['datestamp'] = "";
		$this->container['link'] = '/blog';
		$this->container['content'] = '<p>Sorry, the post you were looking for could not be found.  If you think it should be here, try browsing by title.  Otherwise, <a href="blog/index.php">return to blog home.</a></p>'."\n".'<p>Think you were looking for something else? <a href="'.getConfigOption('site_domain').'">visit site home</a>.</p>';
		
		if ( $nodefile == 'latest' ){
			$nodes = getPostList();
			$nodefile = getConfigOption('post_directory').'/'.$nodes[0];
		}
			
		if (file_exists("$nodefile.json")){
			$jsoncontents = file_get_contents("$nodefile.json");
			
			// Directly read data into the class
			$this->container = json_decode($jsoncontents, True);
			
			// Reformat and add data that the class relies on
			
			// Implode the array of lines for the content into a string
			$this->container['content'] = implode( $this->container['content'] );
			// Add the web url for the post
			$this->container['link'] = getConfigOption('site_domain').'/blog/read/'.basename($nodefile, '.json').'.htm';
			
		}
		/*
		 * This else statement allows the blog platform
		 * to support using plaintext files for post data, which
		 * is nasty.  Use json, it's better.  For a plaintext post,
		 * the syntax is: 
		 * 
		 * TITLE
		 * DISPLAY DATE
		 * TAGS
		 * FEED DATESTAMP
		 * CONTENT
		 */
		else{
			if ( file_exists($nodefile) ){
				
				$file = fopen($nodefile, 'r');
				
				$this->container['title'] = rtrim(fgets($file), "\n");
				$this->container['date'] = rtrim(fgets($file), "\n");
				$this->container['tags'] = rtrim(fgets($file), "\n");
				$this->container['datestamp'] = rtrim(fgets($file), "\n"); 
				$this->container['link'] = getConfigOption('site_domain').'/blog/read/'.basename($nodefile).'.htm';
				$contents='';
			
				while(!feof($file)){
					$contents .= "<p>".rtrim(fgets($file), "\n"). "</p>\n";
				}
			
				$this->container['content'] = $contents;
			
				fclose($file);
			}
		}
	}
	
	/**
	 * Relies a little on modifications that haven't been
	 * made yet.
	 */
	public function output( $type ){
		
		return $this->$type();
	}
	
	/**
	* Produces the coded output of the item that can be 
	* returned and displayed or saved in an atom feed
	*/
	private function atom() {

		$r = "<entry>";
		$r .= "<id>" . $this->container['link'] . "</id>";
		$r .= '<link href="'.$this->container['link'].'" />';
		$r .= '<updated>'.$this->container['datestamp'].'</updated>';
		$r .= "<title>" . $this->container['title'] . "</title>";
		$r .= "<content type='html'>" . htmlspecialchars( $this->container['content'] ) . "</content>";
		$r .= "</entry>";
		return $r;
	}
	
	/**
	 * Produces the coded output of the item that can be returned
	 * and displayed or saved in an rss feed
	 */
	private function rss(){
		
		$r = "<item>";
		$r .= "<title>" . $this->container['title'] ."</title>";
		$r .= "<link>" . $this->container['link'] . "</link>";
		# Produces a "description" by taking the first 100 characters of the content
		$r .= "<description>" . substr( htmlspecialchars( $this->container['content'] ), 0, 100 ) . "...</description>";
		$r .= "</item>";
		
		return $r;
		
	}
	
	/**
	* Produces the coded output of the item that can be displayed
	* on an html page
	*/
	private function html() {
		
		$r = '<h3 class="title"><a href="'.$this->container['link'].'">'.$this->container['title'].'</a></h3>'."\n";
		$r .= '<h4 class="date">'.$this->container['date'].'</h4>'."\n";
		$r .= $this->container['content'];
		if ( $this->container['datestamp'] != ""){
			$r .= "<h5 class='tags'>Tags: ".$this->container['tags']."</h5>\n";
		}
		return $r;
	}

	/**
	 * Returns a string containing the post title
	 * and tags, suitable for outputting in an atom feed (maybe)
	 * or an html list
	 */
	public function list_item_output(){

		 $r = '<a href="'. $this->container['link'] .'">' . $this->container['title'] . '</a><i> - '. $this->container['tags'] .'</i>';
		 return $r;
	 }
 }
 
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
		
		$postData = new postObj("$postDir/$input");

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
		$nextpost = new postObj( getConfigOption('post_directory').'/'.$posts[$i] );
		echo $nextpost->output( 'html' );
		echo "<hr />";
	}
	if (! $start <= 0) echo "<a href='?start=".($start - getConfigOption('posts_per_page') )."&amp;end=".($end - getConfigOption('posts_per_page') )."'>Newer Posts</a>";
	if (! $start <= 0 and count($posts) != $i ) echo ' / ';
	if ( count($posts) != $i ) echo "<a href='?start=".($start + getConfigOption('posts_per_page') )."&amp;end=".($end + getConfigOption('posts_per_page') )."'>  Older Posts</a>  ";

}
?>
