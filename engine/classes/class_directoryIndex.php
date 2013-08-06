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
class directoryIndex{

	/**
	 * @var $directory - the directory inventory we want
	 */
	protected $directory;
	/**
	 * @var $current - whether the inventory appears to be up to date
	 */
	protected $current = null;
	/**
	 * @var $inventoryFile - the file where the inventory is stored (autogen)
	 */
	protected $inventoryFile;
	/**
	 * @var $indexData - the parsed inventory data that the class accesses
	 */
	protected $indexData;
	/**
	 * @var $bloguri - the uri that returns the requested pages
	 */
	protected $bloguri;
	/**
	 * @var $metadata - additional data to store in the index
	 */
	protected $metadata;

	/**
	 * Constructs an instance of the class
	 * @param $directory - the directory of which the inventory is of
	 */
	public function __construct( $directory, $bloguri=NULL, $type="index"  ){
		$this->bloguri = $bloguri;
		$this->directory = $directory;
		$this->inventoryFile = getConfigOption('dynamic_directory').'/'.str_replace('/', '_', $directory).'.'.$type.'.json';

		$jsonData = json_decode( file_get_contents($this->inventoryFile, True), True );

		$this->indexData = $jsonData['inventory'];
		$this->metadata = $jsonData['metadata'];

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

				$recorded = count( $this->indexData );
				$existing = count( $this->getFileList() );

				if ( $recorded == $existing ){
					$this->current = True;
				}

			}

		}

		return $this->current;

	}

	private function get_added_items(){

		$added = array();
		$files = $this->getFileList();

		foreach( $files as $item ){

			if ( ! array_key_exists($item, $this->indexData) )
				$added[] = $item;

		}

		return $added;

	}

	private function get_removed_items(){
		$removed = array();
		$files = $this->getFileList();

		foreach( $this->indexData as $key => $value ){
			if ( ! in_array($key, $files) )
				$removed[] = $key;
		}
		return $removed;
	}

	public function update( $articleDataProvider ){

		if ( !$this->current() ){

			$files = $this->getFileList();

			$inventoryItems = $this->indexData;

			$added = $this->get_added_items($inventoryItems, $files);
			$removed = $this->get_removed_items($files, $inventoryItems);

			foreach ( $removed as $input ){
				unset( $inventoryItems[$input] );
			}

			foreach ($added as $input) {

					$postData = new article("$this->directory/$input", $this->bloguri );
					$inventoryItems["$input"] = $postData->$articleDataProvider();
			}

			krsort( $inventoryItems );
			$this->indexData = $inventoryItems;
			$this->current = True;

			$this->write();
		}

	}

	/**
	 * Regenerates the blog inventory file
	 */
	public function regen( $articleDataProvider, $metadata ){

	
		$avoid = getConfigOption('hidden_files');
		
		$files = $this->getFileList();

		$inventoryItems = array();
	
		foreach( $files as $input ){

			if ( ! in_array($input, $avoid) && ! array_key_exists($input, $inventoryItems) ){ 
		
				$postData = new article("$this->directory/$input", $this->bloguri );
				$inventoryItems[$input] = $postData->$articleDataProvider();
			}
		}
	
		$this->indexData = $inventoryItems;
		$this->metadata = $metadata;

		$this->current = True;

		$this->write();
	}

	public function setMetadata( $metadata ){
		$this->metadata = $metadata;
	}

	/**
	 * Writes the inventory data out to the file
	 * @since 06/11/2013
	 */
	private function write(){
		// Create an instance of a lock
		$lock = new lock( $this->inventoryFile );

		// Check if locked, and if not, set the lock
		// and rewrite the file with the new inventory.
		// Otherwise, update the live instance only
		if ( !$lock->isLocked() ){

			$lock->lock();

			$inventory = fopen( $this->inventoryFile, 'w');

			$dataMap = array();
			$dataMap['inventory'] = $this->indexData;
			$dataMap['metadata'] = $this->metadata;

			fwrite( $inventory, json_encode($dataMap, True) );
			fclose($inventory);

			$lock->unlock();

		}
	}

	/**
	 * Returns the total number of items
	 * in the index.
	 */
	public function getCount(){
		return count($this->indexData);
	}
}

?>
