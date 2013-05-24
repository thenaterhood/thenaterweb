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
include_once 'core_web.php';
include_once 'class_article.php';

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
class inventory{

	/**
	 * @var $directory - the directory inventory we want
	 */
	private $directory;
	/**
	 * @var $current - whether the inventory appears to be up to date
	 */
	private $current = null;
	/**
	 * @var $inventoryFile - the file where the inventory is stored (autogen)
	 */
	private $inventoryFile;
	/**
	 * @var $inventoryData - the parsed inventory data that the class accesses
	 */
	private $inventoryData;
	/**
	 * @var $bloguri - the uri that returns the requested pages
	 */
	private $bloguri;

	/**
	 * Constructs an instance of the class
	 * @param $directory - the directory of which the inventory is of
	 */
	public function __construct( $directory, $bloguri ){
		$this->bloguri = $bloguri;
		$this->directory = $directory;
		$this->inventoryFile = getConfigOption('dynamic_directory').'/INVENTORY-'.str_replace('/', '_', $directory).'.json';

		$this->inventoryData = json_decode( file_get_contents($this->inventoryFile, True) );

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


	}

	/**
	* Checks the number of files in the current directory and
	* compares it to how many are listed in the current inventory.
	* If the number doesn't match, it returns False.
	*/
	public function current(){

		if ( is_null($this->current) ){
			$this->current = False;

			if ( file_exists( $this->inventoryFile ) ){

				$recorded = count( $this->inventoryData );
				$existing = count( $this->getFileList() );

				if ( $recorded == $existing ){
					$this->current = True;
				}

			}

		}

		return $this->current;

	}

	/**
	 * Regenerates the blog inventory file
	 */
	public function regen(){

		$inventory = fopen( $this->inventoryFile, 'w');
	
		$avoid = getConfigOption('hidden_files');
		
		$files = $this->getFileList();

		$inventoryItems = array();
		$filesInArray = array();
	
		foreach( $files as $input ){

			if ( ! in_array($input, $avoid) && ! in_array($input, $filesInArray) ){ 
		
				$postData = new article("$this->directory/$input", $this->bloguri );
				$inventoryItems[] = $postData->getMeta();
				$filesInArray[] = $input;
			}
		}
	
		fwrite( $inventory, json_encode($inventoryItems) );

		fclose($inventory);
		$this->current = True;
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

		for ( $i = 0; $i < count( $this->inventoryData ); ++$i ){

			$current = $this->inventoryData[$i];
			if ( ! is_array( $current->$field ) ){
				$currentData = explode( ', ', $current->$field );
			}
			else{
				$currentData = $current->$field;
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

		for( $i = 0; $i < count( $this->inventoryData ); ++$i ){

			$current = $this->inventoryData[$i];
			if ( ! is_array( $current->$field ) ){
				$currentField = explode( ', ', $current->$field );
			}
			else{
				$currentField = $current->$field;
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
		return $this->inventoryData;
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
