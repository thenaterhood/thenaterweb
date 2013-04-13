<?php
/**
 * Contains utilities and classes for generating an atom feed. Relies
 * on the postObj class for retrieving and outputting post data in the
 * feed. Requires existing instances of the config and session classes.
 * 
 * @author Nate Levesque <public@thenaterhood.com>
 * Language: PHP
 * Filename: core_feed.php
 * 
 */

/**
 * Defines a data object to contain an atom feed as items
 * are added and the feed is updated then returned
 */
class feed {
	
	/**
	 * @var $items - an array of postObj instances
	 * @var $feedMeta - the feed metadata - location, title, author, datestamps
	 */

	private $feedMeta, $items;
	
	/**
	 * Creates an empty atom feed object with metadata
	 * 
	 * @param $title (str): a title for the atom feed
	 * @param $link (str): the base url for the feed
	 * @param $description (str): a description or summary of the feed
	 * @param $feedstamp (str): a datestamp for the feed, in standard atom format
	 */
	public function __construct($title, $link, $description, $feedstamp) {

		$this->feedMeta['title'] = $title;
		$this->feedMeta['link'] = $link;
		$this->feedMeta['description'] = $description;
		$this->feedMeta['feedstamp'] = $feedstamp;
		$this->feedMeta['author'] = $config->site_author;
		$this->items = array();

	}

	/**
	 * Adds an item to the feed as an object in the object's
	 * items array
	 * 
	 * @param $postObject - a fully initialized instance of the postObj
	 *	class.
	 * 
	 */
	public function new_item($postObject) {

		array_push($this->items, $postObject);
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
	 * with appropriate code added for an atom feed.  Relies on the postObj 
	 * class to generate code for individidual
	 * feed items.
	 * 
	 */
	private function atom() {

		$r ='<feed xmlns="http://www.w3.org/2005/Atom"
xml:lang="en"
xml:base="'.getConfigOption('site_domain').'/">';
		$r .= "\n";
		$r .= '<subtitle type="html">' . $this->feedMeta['description'] . "</subtitle>\n";
		$r .= "";
		$r .= "<id>" . $this->feedMeta['link'] . "</id>\n";
		$r .= "<title>" . $this->feedMeta['title'] . "</title>\n";
		$r .= "<updated>". $this->feedMeta['feedstamp'] ."</updated>\n";
		$r .= "<author><name>".$this->feedMeta['author']."</name></author>\n";
		foreach ($this->items as $item) {
			$r .= $item->atom_output();
		}
		$r .= "</feed>";
		return $r;
	}
	
	/**
	 * Returns a displayable representation of the feed with 
	 * appropriate code added for RSS format.
	 */
	private function rss() {
		
		$r ='<?xml version="1.0"?>';
		$r .= '<rss version = "2.0">\n';
		$r .= "<channel>";
		$r .= "<title>" . $this->feedMeta['title'] . "</title>";
		$r .= "<link>" . $this->feedMeta['link'] . "</link>";
		$r .= "<description>" . $this->feedMeta['description'] . "</description>";
		foreach ($this->items as $item){
			$r .= $item->rss_output();
		}
		
		$r .= "</channel>";
		$r .= "</rss>";		
		
		return $r;
	}

}

/**
 * Generates an atom feed and returns it
 * 
 * @return $atom (atom_feed): an instance of the atom_feed class
 * 
 */
function generateFeed(){
	
	$posts = getPostList();
	$atom = new feed("The Philosophy of Nate", "http://blog.thenaterhood.com/", "It's the cyber age, stay in the know.", date(DATE_ATOM) );
	
	for ($i = 0; $i < count($posts); $i++){
		$newitem = new postObj(getConfigOption('post_directory').'/'.$posts[$i]);
		$atom->new_item($newitem);
	}
	return $atom;
}

?>
