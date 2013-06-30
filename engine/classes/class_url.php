<?php
/**
 * Provides a resource for storing a representation of a page
 * on a website with the location and modification date with
 * the facilities to return it in xml format. Mainly for use in
 * a sitemap.
 */

/**
 * Includes the inherited class
 */
include_once GNAT_ROOT.'/classes/class_dataMonger.php';

/**
 * Represents a url object to store a sitemap url block
 */
class url extends dataMonger{

	/**
	 * Creates the data object to contain the atom feed item.
	 * 
	 * @param $link (str): the web address of the item source
	 * @param $lastmod (str): the item modification date
	 */
	public function __construct($link, $lastmod) {

		$this->container['loc'] = $link;
		$this->container['lastmod'] = $lastmod;
	 
	}

	/**
	 * Produces output in the requested format, defaulting to xml
	 * @param $output - ignored
	 */
	public function output( $type='xml' ){

		return $this->$type();

	}
	
	/**
	 * Produces the coded output of the item that can be 
	 * returned and displayed or saved
	 * 
	 * @return $item - an xml-encoded representation of the item
	 */
	private function xml() {

		$item = "<url>\n";
		$item .= "<loc>" . $this->container['loc'] . "</loc>\n";
		$item .= '<lastmod>'.$this->container['lastmod']."</lastmod>\n";
		$item .= "</url>\n";
		return $item;
	}

}
?>