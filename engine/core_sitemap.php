<?php
/**
 * Contains classes for constructing an xml sitemap
 * 
 * @author Nate Levesque <public@thenaterhood.com>
 * @copyright Nate Levesque 2013
 * 
 * Language: PHP
 * Filename: core_sitemap.php
 * 
 */

/**
 * Include classes and functions from core_web
 */
 include 'core_web.php';
 
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

/**
 * Generates a sitemap given a list of local and web paths
 * which correspond to each other
 * 
 * @param $localpath (list): a list of local paths to search for files in
 * @param $webpath (list): a list of web addresses, which correspond to the localpaths
 * @param $delimeters (list): a list of file prefixes to search for in the dir
 * 
 * @return $sitemap (sitemap): an xml sitemap
 */
function createSitemap($localpath, $webpath, $delimeters){

	$sitemap = new urlset();
	
	for ($i = 0; $i < count($localpath); $i++){
		$path = $localpath[$i];
		$dir = opendir("$path");
		$search = $delimeters[$i];
		while ( $file = readdir($dir) ) {

				if ( strpos($file, $search) === 0 and !in_array( $file, getConfigOption('hidden_files') ) ){
					$pageName = explode(".", substr($file,strpos($file, '_')+1) );
					$last_modified = filemtime("$path/$file");
					$sitemap->new_item("$webpath[$i]$pageName[0]", date(DATE_ATOM, $last_modified));
				}
		} 
	}
	
	return $sitemap;	
}

?>
