<?php
/*
* Author: Nate Levesque <public@thenaterhood.com>
* Language: PHP
* Filename: core_blog.php
* 
* Description:
*	Contains classes and functions for retrieving and displaying
*	blog posts and other aspects of the blog platform
*/

include 'core_web.php';

class postObj {
	/*
	* Contains everything to do with retrieving and outputting
	* posts in multiple forms.  Is capable of retrieving posts stored
	* in .json format (preferred when available) as well as plaintext
	* (file syntax described below in constructor).
	* 
	* Contains functions to output the post data in html format
	* for displaying to a page, and atom format for use in generating
	* an atom feed.
	*/
	private $title, $tags, $date, $datestamp, $content, $link;
	
	public function __construct($nodefile){
		/*
		* Reads in a nodefile and returns a data object
		* containing all of the data from it.
		* Accepts plaintext and json, but will prefer json
		* over plaintext because it is superior.
		*/
		
		/* Handles the case where the post file does not exist
		 * at all by pre-setting all the fields to a failure state.
		 * This also safely handles any case where the data in a post
		 * doesn't contain all of the expected fields in a typical way.
		 */
		
		$this->title = "Oops! Post Not Found!";
		$this->date = "";
		$this->tags = "";
		$this->datestamp = "";
		$this->link = 'index.php';
		$this->content = '<p>Sorry, the post you were looking for could not be found.  If you think it should be here, try browsing by title.  Otherwise, <a href="blog/index.php">return to blog home.</a></p>'."\n".'<p>Think you were looking for something else? <a href="'.getConfigOption('site_domain').'">visit site home</a>.</p>';
		
		if ( $nodefile == 'latest' ){
			$nodes = getPostList();
			$nodefile = getConfigOption('post_directory').'/'.$nodes[0];
		}
			
		if (file_exists("$nodefile.json")){
			$jsoncontents = file_get_contents("$nodefile.json");
			$json_array = json_decode($jsoncontents, True);
			
			$this->title = $json_array['title'];
			$this->date = $json_array['date'];
			$this->tags = $json_array['tags'];
			$this->datestamp = $json_array['datestamp'];
			$this->content = implode($json_array['content']);
			$this->link = getConfigOption('site_domain').'/blog/post.php?node='.basename($nodefile, '.json');
			
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
				$this->title = rtrim(fgets($file), "\n");
				$this->date = rtrim(fgets($file), "\n");
				$this->tags = rtrim(fgets($file), "\n");
				$this->datestamp = rtrim(fgets($file), "\n"); 
				$this->link = getConfigOption('site_domain').'/blog/post.php?node='.basename($nodefile);
				$contents='';
			
				while(!feof($file)){
					$contents .= "<p>".rtrim(fgets($file), "\n"). "</p>\n";
				}
			
				$this->content = $contents;
			
				fclose($file);
			}
		}
	}
		
	public function atom_output() {
		/*
		* Produces the coded output of the item that can be 
		* returned and displayed or saved in an atom feed
		*/
		$r = "<entry>";
		$r .= "<id>" . $this->link . "</id>";
		$r .= '<link href="'.$this->link.'" />';
		$r .= '<updated>'.$this->datestamp.'</updated>';
		$r .= "<title>" . $this->title . "</title>";
		$r .= "<content type='html'>" . htmlspecialchars( $this->content ) . "</content>";
		$r .= "</entry>";
		return $r;
	} 
	
	public function page_output() {
		/*
		* Produces the coded output of the item that can be displayed
		* on an html page
		*/
		$r = '<h3 class="title"><a href="'.$this->link.'">'.$this->title.'</a></h3>'."\n";
		$r .= '<h4 class="date">'.$this->date.'</h4>'."\n";
		$r .= $this->content;
		if ( $this->datestamp != ""){
			$r .= "<h5 class='tags'>Tags: ".$this->tags."</h5>\n";
		}
		return $r;
	}

	public function list_item_output(){
		/*
		 * Returns a string containing the post title
		 * and tags, suitable for outputting in an atom feed (maybe)
		 * or an html list
		 */
		 $r = '<a href="'. $this->link .'">' . $this->title . '</a><i> - '. $this->tags .'</i>';
		 return $r;
	 }
	
	public function __get($field){
		/*
		 * Retrieves and returns the requested field
		 */
		return $this->$field;
	}
 }
 
function getPostList(){
	/*
	* Creates a list of files in the working directory, sorts
	* and reverses the list, and returns it.  Intended for working
	* with blog posts stored as text files with date-coded filenames
	*/
	
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

function checkInventory(){
	/*
	* Checks the number of files in the current directory and
	* compares it to how many are listed in the current inventory.
	* If the number doesn't match, it returns False.
	*/
	
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

function regenInventory(){
	/*
	* Regenerates the inventory file
	*/
	
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

function RandomLine($filename) {
	/*
	* Returns a random line of a given file.
	* Used mainly for generating random suggestions 
	* for additional blog posts to read.
	*/
	$lines = file($filename) ;
	return $lines[array_rand($lines)] ;
}

function getSuggestions($number, $tag){
	/*
	* Generates and displays a list of additional 'suggested' blog
	* posts.  Right now picks them randomly, but in the future might
	* rely on a better algorithm.
	* 
	* Arguments:
	*  $number (int): how many to generate and display
	*  $tag (string): a tag or tags to use for generating suggestions
	*/
		$i = 0;
		while ($i < $number){
			echo RandomLine(getConfigOption('dynamic_directory')."/inventory.html");
			$i++;
		}
		
	}
	
function relevantImage($tag){
	/*
	* Retrieves and displays an image relevant to the 
	* post being displayed, based on the first tag
	*/
	
	
}

function getPosts($start, $end){
	/*
	 * Lists the files in a directory and returns an array of them
	 * out to the given length section
	 * 
	 * Arguments:
	 *  $section (int): a range of posts to retrieve
	 * Returns:
	 *  $posts (array): an array of posts retrieved
	 * 
	*/
	$posts = getPostList();
	
	for ($i = $start; $i < count($posts) && $i < $end; $i++){
		$nextpost = new postObj( getConfigOption('post_directory').'/'.$posts[$i] );
		echo $nextpost->page_output();
		echo "<hr />";
	}
	if (! $start <= 0) echo "<a href='?start=".($start - getConfigOption('posts_per_page') )."&amp;end=".($end - getConfigOption('posts_per_page') )."'>Newer Posts</a>";
	if (! $start <= 0 and count($posts) != $i ) echo ' / ';
	if ( count($posts) != $i ) echo "<a href='?start=".($start + getConfigOption('posts_per_page') )."&amp;end=".($end + getConfigOption('posts_per_page') )."'>  Older Posts</a>  ";

}
?>
