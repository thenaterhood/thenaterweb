<?php
/**
 * Provides a resource for storing a representation of a page
 * on a website with the location and modification date with
 * the facilities to return it in xml format. Mainly for use in
 * a sitemap.
 */

namespace Naterweb\Content\Generators\Sitemap;

/**
 * Represents a url object to store a sitemap url block
 */
class Url {
	
	private $weblink;
	private $lastmod;

	/**
	 * Creates the data object to contain the atom feed item.
	 * 
	 * @param $link (str): the web address of the item source
	 * @param $lastmod (str): the item modification date
	 */
	public function __construct( $link, $lastmod ) {

		$this->weblink = $link;
		$this->lastmod = $lastmod;
	 
	}
	
	public function getWeblink()
	{
	    return $this->weblink;
	}
	
	public function getLastmod()
	{
	    return $this->lastmod;
	}
	
	/**
	 * Produces the coded output of the item that can be 
	 * returned and displayed or saved
	 * 
	 * @return $item - an xml-encoded representation of the item
	 */
	public function toXml() {

		$item = "<url>\n";
		$item .= "<loc>" . htmlentities($this->container['loc'] ). "</loc>\n";
		$item .= '<lastmod>'.$this->container['lastmod']."</lastmod>\n";
		$item .= "</url>\n";
		return $item;
	}

	public function toHtml(){

		$html = '<a href="'.htmlentities($this->container['loc']).'">'.$this->container['loc'].'</a>';
		return $html;
	}



}
?>