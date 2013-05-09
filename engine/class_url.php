<?php

include_once 'class_dataMonger.php';

/**
 * Creates a class to contain an item in the sitemap
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
	 * Produces the coded output of the item that can be 
	 * returned and displayed or saved
	 * @param $output - ignored
	 * 
	 * @return $item - an xml-encoded representation of the item
	 */
	public function output( $output ) {

		$item = "<url>\n";
		$item .= "<loc>" . $this->container['loc'] . "</loc>\n";
		$item .= '<lastmod>'.$this->container['lastmod']."</lastmod>\n";
		$item .= "</url>\n";
		return $item;
	}

}
?>