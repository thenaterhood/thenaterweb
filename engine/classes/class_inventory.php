<?php
/**
 * Provides a self-sufficient file with the inventory
 * class, which abstracts access to a data file containing
 * an index of posts or articles in a directory.
 */

/**
 * Includes the necessary facilities for managing
 * the inventory
 */
include_once GNAT_ROOT.'/lib/core_web.php';
include_once GNAT_ROOT.'/classes/class_article.php';
include_once GNAT_ROOT.'/classes/class_directoryIndex.php';

/**
 * Provides a database-like means of accessing an inventory
 * of posts or articles in a directory for efficiently searching
 * for things by certain criteria, as well as maintaining
 * and updating the data. Stored in json format currently, but
 * due to being abstracted out, could be modified to store
 * the data in other formats.
 *
 * @since 5/13/2013
 * @author Nate Levesque <public@thenaterhood.com>
 */
class inventory extends directoryIndex{


	public function __construct( $directory, $bloguri=NULL ){

		parent::__construct( $directory, $bloguri, "inventory" );

	}


	public function update(){

		parent::update( "getMeta" );

	}

	/**
	 * Regenerates the blog inventory file
	 */
	public function regen(){

		parent::regen( "getMeta", array() );

	}

	/**
	 * Returns all the inventory items that match a
	 * requested value in the requested field
	 *
	 * @param $field - the field to search in
	 * @param $value - the value to search for
	 *
	 * @return $matching - all the matching items in the inventory
	 */
	public function select( $field, $value ){

		$matching = array();

		foreach ($this->indexData as $current) {

			if ( ! is_array( $current[$field] ) ){
				$currentData = explode( ', ', $current[$field] );
			}
			else{
				$currentData = $current[$field];
			}

			if ( in_array($value, $currentData) ){
				$matching[] = $current;
			}
		}

		return $matching;

	}

	/**
	 * Returns an array containing the data from a 
	 * particular field, with repeats filtered out
	 *
	 * @param $field - the name of the field to access
	 */
	public function selectField( $field ){

		$fieldContents = array();

		foreach ($this->indexData as $current ) {

			if ( ! is_array( $current[$field] ) ){
				$currentField = explode( ', ', $current[$field] );
			}
			else{
				$currentField = $current[$field];
			}

			foreach ($currentField as $item) {
				if ( ! in_array($item, $fieldContents) )
					$fieldContents[] = $item;
			}

		}

		return $fieldContents;

	}

	/**
	 * Returns all the fields in the inventory
	 */
	public function selectAll(){
		return $this->indexData;
	}
	/**
	 * A function to return the inventory file. For supporting
	 * legacy functions
	 */
	public function getFile(){
		return $this->inventoryFile;
	}

}

?>
