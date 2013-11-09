<?php
/**
 * Provides a resource for building an rss or
 * atom feed.
 */

/**
 * Include the inherited dataMonger class
 */
include_once GNAT_ROOT.'/classes/class_dataMonger.php';
include_once GNAT_ROOT.'/classes/class_article.php';
include_once GNAT_ROOT.'/classes/class_mappedArticle.php';
include_once GNAT_ROOT.'/classes/class_stdClassArticle.php';
include_once GNAT_ROOT.'/classes/class_lock.php';
include_once GNAT_ROOT.'/classes/class_directoryIndex.php';

/**
 * Defines a data object to contain an atom feed as items
 * are added and the feed is updated then returned
 */
class feed extends directoryIndex{

	private $articles;
	
	/**
	 * Creates an empty atom feed object with metadata
	 * 
	 * @param $title (str): a title for the atom feed
	 * @param $link (str): the base url for the feed
	 * @param $description (str): a description or summary of the feed
	 * @param $feedstamp (str): a datestamp for the feed, in standard atom format
	 */
	public function __construct( $directory, $bloguri ) {

		parent::__construct( $directory, $bloguri, "feed" );


	}

	public function update(){

		$this->loadArticles();


		parent::update( "dump" );
	}

	private function loadArticles(){

		foreach ($this->db->selectTable( 'main' ) as $item) {
			
			$this->articles[] = new mappedArticle( $item );

		}

	}



	public function reset($title, $link, $description, $feedstamp){

		$dbCols = array();
		$dbCols['content'] = 'Text';
		$dbCols['title'] = 'Text';
		$dbCols['date'] = 'Text';
		$dbCols['tags'] = 'Text';
		$dbCols['datestamp'] = 'Text';
		$dbCols['updated'] = 'Text';
		$dbCols['link'] = 'Text';
		$dbCols['nodeid'] = 'Text';

		$this->db->dropTable( 'main' );
		$this->db->createTable( 'main', $dbCols )

		$metadata = array();
		$metadata['title'] = $title;
		$metadata['link'] = $link;
		$metadata['description'] = $description;
		$metadata['feedstamp'] = $feedstamp;
		$metadata['author'] = getConfigOption('site_author');

		parent::regen( "dump", $metadata );



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

		$this->loadArticles();


		$r = '<?xml version="1.0" encoding="UTF-8"?>';
		$r .='<feed xmlns="http://www.w3.org/2005/Atom"
xml:lang="en"
xml:base="'.getConfigOption('site_domain').'/">';
		$r .= "\n";
		$r .= '<subtitle type="html">' . $this->metadata['description'] . "</subtitle>\n";
		$r .= "";
		$r .= "<id>" . $this->metadata['link'] . "</id>\n";
		$r .= "<title>" . $this->metadata['title'] . "</title>\n";
		$r .= "<updated>". $this->metadata['feedstamp'] ."</updated>\n";
		$r .= "<author><name>".$this->metadata['author']."</name></author>\n";
		$r .= '<atom10:link xmlns:atom10="http://www.w3.org/2005/Atom" rel="self" type="application/atom+xml" href="'.$this->metadata['link'].'/feed.php" />';
		foreach ($this->articles as $item) {
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

		$this->loadArticles();

		
		$r ='<xml version="1.0">';
		$r .= '<rss version = "2.0">\n';
		$r .= "<channel>";
		$r .= "<title>" . $this->metadata['title'] . "</title>";
		$r .= "<link>" . $this->metadata['link'] . "</link>";
		$r .= "<description>" . $this->metadata['description'] . "</description>";
		foreach ($this->articles as $item){
			$r .= $item->output( 'rss' );
		}
		
		$r .= "</channel>";
		$r .= "</rss>";		
		
		return $r;
	}

}

?>