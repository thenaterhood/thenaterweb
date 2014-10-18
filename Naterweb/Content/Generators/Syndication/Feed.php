<?php
/**
 * Provides a resource for building an rss or
 * atom feed.
 */

namespace Naterweb\Content\Generators\Syndication;

require_once NWEB_ROOT.'/Content/Loaders/class_contentFactory.php';

/**
 * Defines a data object to contain an atom feed as items
 * are added and the feed is updated then returned
 */
abstract class Feed {

	protected $items;
	protected $title;
	protected $description;
	protected $generationTime;
	protected $author;
	protected $feedUrl;

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
		$this->feedUrl = \Naterweb\Engine\Configuration::get_option('site_domain').'/'.$link.'/feed';
		$this->description = $description;
		$this->generationTime = $feedstamp;
		$this->author = \Naterweb\Engine\Configuration::get_option('site_author');
		$this->items = array();

	}

	/**
	 * Adds an item to the feed as an object in the object's
	 * items array
	 * 
	 * @param $articleect - a fully initialized instance of an stdclass
	 * containing the data for the feed item
	 * 
	 */
	public function new_item($article) {

		array_push($this->items, $article);
	}

	public abstract function render();

}
?>
