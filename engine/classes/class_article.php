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
include_once GNAT_ROOT.'/classes/class_dataMonger.php';
include_once GNAT_ROOT.'/lib/core_web.php';
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
	private $usePostFormat;
	private $type;
	private $file;
	
	/**
	 * Reads and parses a post file and creates an instance
	 * of the class with the post data. Capable of managing
	 * posts in json and plaintext, but prefers json if
	 * a json file exists for the requested post.
	 * 
	 * @param nodefile (string) - a yyyy.mm.dd string of a nodefile
	 */
	public function __construct( $nodefile, $bloguri, $articleUri="", $from_db='auto' ){

		/* Handles the case where the post file does not exist
		 * at all by pre-setting all the fields to a failure state.
		 * This also safely handles any case where the data in a post
		 * doesn't contain all of the expected fields in a typical way.
		 */
		$this->usePostFormat = True;
		$this->type = "none";
		$extStart = strpos( $nodefile, '.' );
		$type = substr($nodefile, $extStart+1);

		$this->blogurl = $bloguri;
		$this->container['title'] = "Holy 404, Batman!";
		$this->container['nodeid'] = $nodefile;
		$this->container['blog_tab'] = str_replace('/', '_', $bloguri);
		$this->container['date'] = "";
		$this->container['tags'] = "";
		$this->container['datestamp'] = "";
		$this->container['link'] = $articleUri;
		if ( $articleUri == "" )
			$this->container['link'] = $bloguri;

		$this->container['content'] = '<p>
		Sorry, seems that you must have taken a wrong turn - the page you tried to visit could not be found!  
		If you think it should be here, try browing by post title, or looking at the sitemap.  
		Otherwise, <a href="/">Return home.</a></p>'."\n";

		# Handle retrieving from a database, if the option is set
		# then fall back on searching for the file if the 
		# item could not be found.

		if ( $from_db == 'auto '){
			$from_db = $CONFIG->use_db;
		}
		
		if ( $from_db ){

			$this->retrieveFromDb( $nodefile );

		} else {

			$this->retrieveFromFile( $nodefile );

		}



	}

	private function retrieveFromDb( $nodefile ){

		Database::initialize();
		
		$nodeData = Database::select( $this->container['blog_tab'], 'title,date,tags,datestamp,content,type',
			 array( 'where' => array( 'id' => $nodefile ), 'singleRow' => 'true' ) );


		foreach ($nodeData as $key => $value) {
			$this->container[ $key ] = $value;
		}

	}

	private function retrieveFromFile( $nodefile ){

		if ( file_exists("$nodefile.json") ){
			$this->type = "json";
			$this->usePostFormat = True;
			$this->container['file'] = $nodefile.'.json';

			$jsoncontents = file_get_contents("$nodefile.json");
			
			// Directly read data into the class
			$this->container = json_decode($jsoncontents, True);

			// Parse the atom datestamp into english
			$this->container['date'] = date( "F j, Y, g:i a", strtotime($this->container['datestamp']) );
			
			// Reformat and add data that the class relies on
			
			// Implode the array of lines for the content into a string
			if ( array_key_exists( 'content', $this->container ) && is_array( $this->container['content'] ) )
				$this->container['content'] = implode( $this->container['content'] );

			// Add the web url for the post
			if ( array_key_exists( 'tags', $this->container ) && is_array( $this->container['tags'] ) )
				$this->container['tags'] = implode( ', ', $this->container['tags'] );

			if ( $articleUri == "" )
				$this->container['link'] = 
					getConfigOption('site_domain').'/?url='.$bloguri.'/read/'.basename($nodefile, '.json').'.htm';
			$this->container['nodeid'] = basename($nodefile, '.json');
			
		} else if ( file_exists( $nodefile.'.html' ) ) {
			$this->container['file'] = $nodefile.'.html';
			$this->usePostFormat = False;
			$this->type = "HTML";
			$this->container['content'] = file_get_contents($nodefile.'.html');
			$title = explode('/', $nodefile);
			$title = $title[ count($title)-1 ];

			$this->container['title'] = substr( $title, strpos( $title, '_' )+1 );
			$this->container['datestamp'] = date( DATE_ATOM, filemtime($nodefile.'.html') );
			$this->container['date'] = date( "F j, Y, g:i a", strtotime($this->container['datestamp']) );

		} else if ( file_exists( $nodefile.'.php' ) ) {
			$this->usePostFormat = False;
			$this->type = "PHP";
			$this->container['file'] = $nodefile.'.php';
                        $title = explode('/', $nodefile);
                        $title = $title[ count($title)-1 ];
                        $this->container['title'] = substr( $title, strpos( $title, '_' )+1 );

			$this->container['datestamp'] = date( DATE_ATOM, filemtime($nodefile.'.php') );
			$this->container['date'] = date( "F j, Y, g:i a", strtotime($this->container['datestamp']) );

		} else if ( file_exists( $nodefile.'.pre' ) ){
			$this->usePostFormat = False;
			$this->container['file'] = $nodefile.'.pre';

			$this->type = "pre";
			$this->container['content'] = '<pre>'."\n".htmlspecialchars( file_get_contents($nodefile.'.pre') )."\n".'</pre>';
                        $title = explode('/', $nodefile);
                        $title = $title[ count($title)-1 ];
                        $this->container['title'] = substr( $title, strpos( $title, '_' )+1 );

			$this->container['datestamp'] = date( DATE_ATOM, filemtime($nodefile.'.pre') );
			$this->container['date'] = date( "F j, Y, g:i a", strtotime($this->container['datestamp']) );
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
				$this->type = "TXT";
				$this->container['file'] = $nodefile.'.txt';

				$file = fopen($nodefile, 'r');
				
				$this->container['title'] = rtrim(fgets($file), "\n");
				$this->container['date'] = rtrim(fgets($file), "\n");
				$this->container['tags'] = rtrim(fgets($file), "\n");
				$this->container['datestamp'] = rtrim(fgets($file), "\n"); 
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

	public function __get( $field ){

		if ( array_key_exists($field, $this->container) ){
			return $this->container[$field];
		}
		else{
			return "";
		}

	}

	public function getType(){
		return $this->type;
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
		$r .= "<title>" . htmlspecialchars( $this->title ) . "</title>";
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

	public function toHtml(){
		return $this->html();
	}

	public function getFile(){
		return $this->container['file'];
	}
	
	/**
	* Produces the coded output of the item that can be displayed
	* on an html page
	*/
	private function html() {
		$r = '';
		if ( $this->type != "PHP" ){
			if ( $this->usePostFormat )
				$r = '<h3 class="title"><a href="'.htmlentities( $this->link ).'">'.$this->title.'</a></h3>'."\n";
			if ( $this->usePostFormat )
				$r .= '<h4 class="date">'.$this->date.'</h4>'."\n";
			$r .= $this->content;

			if ( $this->datestamp != "" && $this->usePostFormat ){
				$r .= "<h5 class='tags'>Tags: ".$this->tags."</h5>\n";
			}

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
		$meta['nodeid'] = $this->container['nodeid'];
		$meta['title'] = $this->title;
		$meta['link'] = $this->link;
		$meta['datestamp'] = $this->datestamp;
		$meta['author'] = $this->author;
		$meta['file'] = $this->file;

		return $meta;

	}

	public function isPhp(){
		if ( $this->type == "PHP" ){
			return True;
		}
		else{
			return False;
		}
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
