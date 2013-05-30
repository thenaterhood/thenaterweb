<?php
/**
 * Provides a resource for building an rss or
 * atom feed.
 */

/**
 * Include the inherited dataMonger class
 */
include_once 'class_dataMonger.php';
include_once 'class_article.php';
include_once 'class_mappedArticle.php';

/**
 * Defines a data object to contain an atom feed as items
 * are added and the feed is updated then returned
 */
class feed extends dataMonger{
	
	/**
	 * @var $items - an array of article instances
	 * @var $container - the feed metadata - location, title, author, datestamps
	 */

	private $items;
	private $cacheFile;
	
	/**
	 * Creates an empty atom feed object with metadata
	 * 
	 * @param $title (str): a title for the atom feed
	 * @param $link (str): the base url for the feed
	 * @param $description (str): a description or summary of the feed
	 * @param $feedstamp (str): a datestamp for the feed, in standard atom format
	 */
	public function __construct( $bloguri ) {

		$this->cacheFile = getConfigOption('dynamic_directory')."/feed-$bloguri";

		if ( $this->exists() ){

			$this->retrieve();

		}

		else{
			$this->reset( "", "", "", "" );
		}

	}

	private function retrieve(){

		$rawJson = json_decode( file_get_contents("$this->cacheFile.json"), True );

		$this->container['title'] = $rawJson['title'];
		$this->container['link'] = $rawJson['link'];
		$this->container['description'] = $rawJson['description'];
		$this->container['feedstamp'] = $rawJson['feedstamp'];
		$this->container['author'] = $rawJson['author'];

		foreach ($rawJson['items'] as $item) {
			
			$this->items[] = new mappedArticle( $item );

		}

	}

	public function save(){

		$saveData = $this->container;
		$saveItems = array();

		foreach ($this->items as $item ) {

			$saveItems[] = $item->dump();
		}

		$saveData['items'] = $saveItems;

		$file = fopen("$this->cacheFile.json", 'w');
		fwrite($file, json_encode($saveData) );
		fclose($file);

	}

	private function cache( $xml ){

		$file = fopen( "$this->cacheFile.xml", 'w' );

		fwrite( $file, $xml );
		fclose($file);

	}

	private function getCache(){

		return file_get_contents("$this->cacheFile.xml");
	}

	public function exists(){

		return file_exists( "$this->cacheFile.json" );
	}

	public function reset($title, $link, $description, $feedstamp){

		$this->container['title'] = $title;
		$this->container['link'] = $link;
		$this->container['description'] = $description;
		$this->container['feedstamp'] = $feedstamp;
		$this->container['author'] = getConfigOption('site_author');
		$this->items = array();


	}

	/**
	 * Adds an item to the feed as an object in the object's
	 * items array
	 * 
	 * @param $articleect - a fully initialized instance of the article
	 *	class.
	 * 
	 */
	public function new_item($articleect) {

		if ( count( $this->items) < 200 ){

			array_push($this->items, $articleect);
		}
	}
	
	/**
	 * Returns a valid feed with the requested format.
	 * 
	 * @param $type - the type of feed to return (atom/rss). Defaults
	 * to atom (superior) if the type given not recognized.
	 */
	public function output( $type ){
		
		if ( $type == "rss" ){
			return $this->rss();
		}
		
		else{
			
			return $this->atom();
		}
	}
	/**
	 * Returns a displayable representation of the feed
	 * with appropriate code added.  Relies on the article 
	 * atom_output() function to generate code for individidual
	 * feed items. Returns ATOM format.
	 * 
	 */
	private function atom() {

		$r ='<feed xmlns="http://www.w3.org/2005/Atom"
xml:lang="en"
xml:base="'.getConfigOption('site_domain').'/">';
		$r .= "\n";
		$r .= '<subtitle type="html">' . $this->container['description'] . "</subtitle>\n";
		$r .= "";
		$r .= "<id>" . $this->container['link'] . "</id>\n";
		$r .= "<title>" . $this->container['title'] . "</title>\n";
		$r .= "<updated>". $this->container['feedstamp'] ."</updated>\n";
		$r .= "<author><name>".$this->container['author']."</name></author>\n";
		$r .= '<atom10:link xmlns:atom10="http://www.w3.org/2005/Atom" rel="self" type="application/atom+xml" href="'.$this->container['link'].'/feed.php" />';
		foreach ($this->items as $item) {
			$r .= $item->output( 'atom' );
		}
		$r .= "</feed>";
		return $r;
	}
	
	/**
	 * Returns a displayable representation of the feed with 
	 * appropriate code added for RSS format.
	 */
	private function rss() {
		
		# The code produced is not valid due to the xml tag 
		# which should have a ? before each <>. This breaks the
		# php.
		
		$r ='<xml version="1.0">';
		$r .= '<rss version = "2.0">\n';
		$r .= "<channel>";
		$r .= "<title>" . $this->container['title'] . "</title>";
		$r .= "<link>" . $this->container['link'] . "</link>";
		$r .= "<description>" . $this->container['description'] . "</description>";
		foreach ($this->items as $item){
			$r .= $item->output( 'rss' );
		}
		
		$r .= "</channel>";
		$r .= "</rss>";		
		
		return $r;
	}



}
?>