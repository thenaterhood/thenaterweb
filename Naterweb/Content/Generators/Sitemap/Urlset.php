<?php
/**
 * Provides a class for creating and containing
 * a sitemap with the ability to export it as xml data.
 * @author Nate Levesque <public@thenaterhood.com>
 */
 
namespace Naterweb\Content\Generators\Sitemap;

/**
 * Include the required url class to use internally
 */
include_once NWEB_ROOT.'/Content/Generators/Sitemap/Url.php';

use Naterweb\Content\Generators\Sitemap\Url;

/**
 * Defines a data object to contain an xml sitemap.
 */
abstract class Urlset {
	
	/**
	 * @var $items - an array of url objects
	 */
	protected $items;
	
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
	public abstract function render();

}
