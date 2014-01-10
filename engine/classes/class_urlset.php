<?php
/**
 * Provides a class for creating and containing
 * a sitemap with the ability to export it as xml data.
 * @author Nate Levesque <public@thenaterhood.com>
 */

/**
 * Include the required url class to use internally
 */
include_once NWEB_ROOT.'/classes/class_url.php';

/**
 * Defines a data object to contain an xml sitemap.
 */
class Urlset {
	
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

		array_push($this->items, new Url($loc, $lastmod));
	}
	
	/**
	* Returns a displayable representation of the sitemap
	* with appropriate code added.
	* 
	* @return $r (string) - an xml encoded output of the class
	*/
	public function toXml() {

		$r ='<?xml version="1.0" encoding="UTF-8"?>'."\n";
		$r .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">'."\n";
		$r .= "\n";
		foreach ($this->items as $item) {
			$r .= $item->toXml();
		}
		$r .= "</urlset>";
		return $r;
	}

	public function toHtml(){

		$r = '<ul>'."\n";

		foreach ($this->items as $item) {
			$r .= '<li>'.$item->toHtml().'</li>'."\n";
		}

		$r .= "</ul>\n";

		return $r;

	}

}
?>