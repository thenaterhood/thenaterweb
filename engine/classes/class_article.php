<?php
/**
 * Provides a consistent interface for
 * accessing posts or articles with multiple methods
 * of displaying and managing the contained data.
 *
 * @author Nate Levesque <public@thenaterhood.com>
 */


/**
 * Includes the inherited dataMonger class
 */
include_once $_SERVER['DOCUMENT_ROOT'].'/engine/classes/class_dataMonger.php';
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
class article extends dataMonger{

	private $blogurl;
	
	/**
	 * Reads and parses a post file and creates an instance
	 * of the class with the post data. Capable of managing
	 * posts in json and plaintext, but prefers json if
	 * a json file exists for the requested post.
	 * 
	 * @param nodefile (string) - a yyyy.mm.dd string of a nodefile
	 */
	public function __construct($nodefile, $bloguri){

		/* Handles the case where the post file does not exist
		 * at all by pre-setting all the fields to a failure state.
		 * This also safely handles any case where the data in a post
		 * doesn't contain all of the expected fields in a typical way.
		 */
		$this->blogurl = $bloguri;
		$this->container['title'] = "Oops! Post Not Found!";
		$this->container['date'] = "";
		$this->container['tags'] = "";
		$this->container['datestamp'] = "";
		$this->container['link'] = '/'.$bloguri;
		$this->container['content'] = '<p>Sorry, the post you were looking for could not be found.  If you think it should be here, try browsing by title.  Otherwise, <a href="blog/index.php">return to blog home.</a></p>'."\n".'<p>Think you were looking for something else? <a href="'.getConfigOption('site_domain').'">visit site home</a>.</p>';
			
		if (file_exists("$nodefile.json")){
			$jsoncontents = file_get_contents("$nodefile.json");
			
			// Directly read data into the class
			$this->container = json_decode($jsoncontents, True);
			
			// Reformat and add data that the class relies on
			
			// Implode the array of lines for the content into a string
			if ( array_key_exists( 'content', $this->container ) && is_array( $this->container['content'] ) )
				$this->container['content'] = implode( $this->container['content'] );

			// Add the web url for the post
			if ( array_key_exists( 'tags', $this->container ) && is_array( $this->container['tags'] ) )
				$this->container['tags'] = implode( ', ', $this->container['tags'] );

			$this->container['link'] = getConfigOption('site_domain').'/'.$bloguri.'/index.php?id=post&node='.basename($nodefile, '.json');
			$this->container['nodeid'] = basename($nodefile, '.json');
			
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
	 * Returns a representation of the post in the format requested
	 * 
	 * @param $type - the type of feed
	 */
	public function output( $type ){
		
		return $this->$type();
	}

	private function get( $field ){

	}
	
	/**
	* Produces the coded output of the item that can be 
	* returned and displayed or saved in an atom feed
	*/
	private function atom() {

		$r = "<entry>";
		# In order to make the feed validate, we pull the http out of the id and append it
		# statically, then urlencode the rest of the url. Otherwise, the feed does not 
		# validate.
		$r .= "<id>http://" . urlencode( substr($this->link, 7) ) . "</id>";
		$r .= '<link href="http://'. htmlspecialchars( substr($this->container['link'], 7) ) .'" />';
		$r .= '<updated>'.$this->datestamp.'</updated>';
		$r .= "<title>" . $this->title . "</title>";
		$r .= "<content type='html'>" . htmlspecialchars( $this->content, ENT_QUOTES ) . "</content>";
		$r .= "</entry>";
		return $r;
	}
	
	/**
	 * Produces the coded output of the item that can be returned
	 * and displayed or saved in an rss feed
	 */
	private function rss(){
		
		$r = "<item>";
		$r .= "<title>" . $this->title ."</title>";
		$r .= "<link>" . $this->link . "</link>";
		# Produces a "description" by taking the first 100 characters of the content
		$r .= "<description>" . substr( htmlspecialchars( $this->content, ENT_QUOTES ), 0, 100 ) . "...</description>";
		$r .= "</item>";
		
		return $r;
		
	}
	
	/**
	* Produces the coded output of the item that can be displayed
	* on an html page
	*/
	private function html() {
		
		$r = '<h3 class="title"><a href="'.$this->link.'">'.$this->title.'</a></h3>'."\n";
		$r .= '<h4 class="date">'.$this->date.'</h4>'."\n";
		$r .= $this->content;
		if ( $this->datestamp != ""){
			$r .= "<h5 class='tags'>Tags: ".$this->tags."</h5>\n";
		}
		return $r;
	}

	/**
	 * Returns the article metadata - tags, title, link, date, and author
	 * as an associative array.
	 * @return $meta - the metadata array
	 */
	public function getMeta(){

		$meta = array();
		$meta['tags'] = $this->tags;
		$meta['title'] = $this->title;
		$meta['link'] = $this->link;
		$meta['datestamp'] = $this->datestamp;
		$meta['author'] = $this->author;

		return $meta;

	}

	/**
	 * Returns a string containing the post title
	 * and tags, suitable for outputting in an atom feed (maybe)
	 * or an html list
	 */
	public function list_item_output(){

		 $r = '<a href="'. $this->link .'">' . $this->title . '</a><i> - '. $this->tags .'</i>';
		 return $r;
	 }
 }
 ?>
