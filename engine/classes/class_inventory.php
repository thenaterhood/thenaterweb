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
<<<<<<< HEAD
		$this->bloguri = $bloguri;
		$this->directory = $directory;
		$this->inventoryFile = getConfigOption('dynamic_directory').'/'.str_replace('/', '_', $directory).'.inventory.json';

		$jsonData = json_decode( file_get_contents($this->inventoryFile, True), True );

		$this->inventoryData = $jsonData;

	}

	/**
	* Creates a list of files in the working directory, sorts
	* and reverses the list, and returns it.  Intended for working
	* with blog posts stored as text files with date-coded filenames
	*/
	public function getFileList(){
		$avoid = getConfigOption('hidden_files');
		$contents = array();

		$handler = opendir($this->directory);
		$i = 0;

		while ($file = readdir($handler)){
			// if file isn't this directory or its parent, or itself, add it to the results
			// We check if it's there already because we're migrating from plaintext to json
			// so there may be duplicates.
			if (  !in_array($file, $avoid) and !in_array($file, $contents) and !in_array(substr($file, 0, -5), $contents) ){
				if ( strpos($file,"json") ){
					$contents[] = substr($file, 0, -5);
				}
			else{
				$contents[] = $file;
			}
				$i++;
			}

		}
	
		sort($contents);
		$contents = array_reverse($contents);
	
		return $contents;
=======
>>>>>>> master

		parent::__construct( $directory, $bloguri, "inventory" );

	}


	public function update(){

<<<<<<< HEAD
		if ( !$this->current() ){

			$files = $this->getFileList();

			$inventoryItems = $this->inventoryData;

			$added = array_diff_key($inventoryItems, $files);
			$removed = array_diff_key($files, $inventoryItems);

			foreach ( $removed as $input ){
				unset( $inventoryItems[$input] );
			}

			foreach ($added as $input) {

					$postData = new article("$this->directory/$input", $this->bloguri );
					$inventoryItems["$input"] = $postData->getMeta();
				# code...
			}

			$this->inventoryData = $inventoryItems;
			$this->current = True;

			$this->write();
		}
=======
		parent::update( "getMeta" );
>>>>>>> master

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
	 * Returns a histogram of values mapped to 
	 * their occurances for the selected field
	 *
	 * @param $field - the name of the field to access
	 * @since 7/20/13
	 */
	public function getFieldStatistics( $field ){

		$fieldContents = array();

		foreach ($this->indexData as $current ) {

			if ( ! is_array( $current[$field] ) ){
				$currentField = explode( ', ', $current[$field] );
			}
			else{
				$currentField = $current[$field];
			}

			foreach ($currentField as $item) {
				if ( ! array_key_exists( $item, $fieldContents) )
					$fieldContents[$item] = 1;
				else
					$fieldContents[$item] = $fieldContents[$item] + 1;
			}

		}

		return $fieldContents;

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
