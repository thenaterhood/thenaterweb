<?php

include_once 'class_url.php';
/**
 * Defines a data object to contain an atom feed as items
 * are added and the feed is updated then returned
 */
class urlset {
	
	/**
	 * @var $items - an array of url objects
	 */
	private $items;
	
	/**
	* Creates an empty sitemap class
	*/
	public function __construct() {
		
		$this->items = array();
	}

	/**
	 * Adds an item to the feed as an object in the object's
	 * items array
	 * 
	 * @param $loc (str): the web address of the item's source
	 * @param $lastmod (str): the modification date of the item
	 */
	public function new_item($loc, $lastmod) {

		array_push($this->items, new url($loc, $lastmod));
	}
	
	/**
	* Returns a displayable representation of the sitemap
	* with appropriate code added.
	* 
	* @return $r (string) - an xml encoded output of the class
	*/
	public function output() {

		$r ='<?xml version="1.0" encoding="UTF-8"?>
		<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">';
		$r .= "\n";
		foreach ($this->items as $item) {
			$r .= $item->output();
		}
		$r .= "</urlset>";
		return $r;
	}

}
?>