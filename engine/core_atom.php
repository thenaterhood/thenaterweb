<?php
/**
 * Contains utilities and classes for generating an atom feed. Relies
 * on the postObj class for retrieving and outputting post data in the
 * feed. Requires existing instances of the config and session classes.
 * 
 * @author Nate Levesque <public@thenaterhood.com>
 * Language: PHP
 * Filename: core_atom.php
 * 
 */

/**
 * Defines a data object to contain an atom feed as items
 * are added and the feed is updated then returned
 */
class atom_feed {
	
	/**
	 * @var $title - the feed title
	 * @var $link - a link to the feed
	 * @var $description - a description or tagline for the feed
	 * @var $feedstamp - the atom format generation date for the feed
	 * @var $author - the author publishing the feed
	 */

	private $title, $link, $description, $feedstamp, $author;
	
	/**
	 * Creates an empty atom feed object with metadata
	 * 
	 * @param $title (str): a title for the atom feed
	 * @param $link (str): the base url for the feed
	 * @param $description (str): a description or summary of the feed
	 * @param $feedstamp (str): a datestamp for the feed, in standard atom format
	 */
	public function __construct($title, $link, $description, $feedstamp) {

		$this->title = $title;
		$this->link = $link;
		$this->description = $description;
		$this->feedstamp = $feedstamp;
		$this->author = $config->site_author;
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
	 * Returns a displayable representation of the feed
	 * with appropriate code added.  Relies on the postObj 
	 * atom_output() function to generate code for individidual
	 * feed items.
	 * 
	 */
	public function output() {

		$r ='<feed xmlns="http://www.w3.org/2005/Atom"
xml:lang="en"
xml:base="'.getConfigOption('site_domain').'/">';
		$r .= "\n";
		$r .= '<subtitle type="html">' . $this->description . "</subtitle>\n";
		$r .= "";
		$r .= "<id>" . $this->link . "</id>\n";
		$r .= "<title>" . $this->title . "</title>\n";
		$r .= "<updated>". $this->feedstamp ."</updated>\n";
		$r .= "<author><name>".$this->author."</name></author>\n";
		foreach ($this->items as $item) {
			$r .= $item->atom_output();
		}
		$r .= "</feed>";
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
	$atom = new atom_feed("The Philosophy of Nate", "http://blog.thenaterhood.com/", "It's the cyber age, stay in the know.", date(DATE_ATOM) );
	
	for ($i = 0; $i < count($posts); $i++){
		$newitem = new postObj(getConfigOption('post_directory').'/'.$posts[$i]);
		$atom->new_item($newitem);
	}
	return $atom;
}

?>
