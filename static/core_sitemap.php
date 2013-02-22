<?php
/*
* Author: Nate Levesque <public@thenaterhood.com>
* Language: PHP
* Filename: core_sitemap.php
* 
* Description:
* 	Contains classes for constructing an xml sitemap
*/

class urlset {
	/*
	* Defines a data object to contain an atom feed as items
	* are added and the feed is updated then returned
	*/
	
	public function __construct() {
		/*
		* Creates an empty atom feed object with metadata
		* 
		* Arguments:
		*  $title (str): a title for the atom feed
		*  $link (str): the base url for the feed
		*  $description (str): a description or summary of the feed
		*  $feedstamp (str): a datestamp for the feed, in standard atom format
		*/
		$this->items = array();

	}

	public function new_item($loc, $lastmod) {
		/*
		* Adds an item to the feed as an object in the object's
		* items array
		* 
		* Arguments:
		*  $title (str): the title of the item
		*  $link (str): the web address of the item's source
		*  $description (str): a description of or the content 
		*	 of the item
		*  $datestamp (str): the official datestamp of the item
		*/
		array_push($this->items, new url($loc, $lastmod));
	}
	
	public function output() {
		/*
		* Returns a displayable representation of the feed
		* with appropriate code added.
		*/
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

class url {
	/*
	* Creates the data object to contain an item in the feed
	*/
	public $loc, $lastmod;
	
	public function __construct($loc, $lastmod) {
		/*
		* Creates the data object to contain the atom feed item.
		* 
		* Arguments:
		*  $title (str): the title of the item
		*  $link (str): the web address of the item source
		*  $description (str): the content of the item
		*  $datestamp (str): the atom-format datestamp of the item
		*/
		$this->loc = $loc;
		$this->lastmod = $lastmod;
	 
	}
	public function output() {
		/*
		* Produces the coded output of the item that can be 
		* returned and displayed or saved
		*/
		$item = "<url>\n";
		$item .= "<loc>" . $this->loc . "</loc>\n";
		$item .= '<lastmod>'.$this->lastmod."</lastmod>\n";
		$item .= "</url>\n";
		return $item;
	}

}

?>
