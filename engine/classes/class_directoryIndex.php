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
include_once NWEB_ROOT.'/lib/core_web.php';
include_once NWEB_ROOT.'/classes/class_article.php';
include_once NWEB_ROOT.'/classes/class_sqlitedb.php';
include_once NWEB_ROOT.'/classes/class_databaseFactory.php';

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
	 *
	 */
	protected $db;

	/**
	 * Constructs an instance of the class
	 * @param $directory - the directory of which the inventory is of
	 */
	public function __construct( $directory, $bloguri=NULL, $type="index"  ){


		$this->bloguri = $bloguri;
		$this->directory = $directory;
		$this->inventoryFile = getConfigOption('dynamic_directory').'/'.str_replace('/', '_', $directory).'.'.$type;

		$this->db = DatabaseFactory::create( getConfigOption( 'engine_storage_db' ), $this->inventoryFile );

		if ( file_exists($this->inventoryFile.'.extradata') )
			$this->metadata = json_decode( file_get_contents($this->inventoryFile.'.extradata', True), True );
		else
			$this->metadata = array();


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


			$recorded = $this->db->getRowCount( 'main' );
			$existing = count( $this->getFileList() );

			if ( $recorded == $existing ){
				$this->current = True;
			}


		}

		return $this->current;

	}

	private function get_added_items(){

		$added = array();
		$files = $this->getFileList();

		foreach( $files as $item ){

			if ( ! $this->db->exists( 'nodeid', $item, 'main' ) )
				$added[] = $item;

		}

		return $added;

	}

	private function get_removed_items(){
		$removed = array();
		$files = $this->getFileList();

		foreach( $this->db->selectTable( 'main' ) as $key => $value ){
			if ( ! in_array($value['nodeid'], $files) )
				$removed[] = $value['nodeid'];
		}
		return $removed;
	}

	public function update( $articleDataProvider ){

		if ( !$this->current() ){

			$added = $this->get_added_items();
			$removed = $this->get_removed_items();

			foreach ( $removed as $input ){
				$this->db->query( 'DELETE * FROM main WHERE nodeid=?', array( $input ) );
			}

			foreach ($added as $input) {

					$postData = new article("$this->directory/$input", $this->bloguri );
					$this->db->insert( 'main', $postData->$articleDataProvider() );
			}

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
				$inventoryItems[$input] = "exists";
				$this->db->insert( 'main', $postData->$articleDataProvider() );
			}
		}
	
		$this->metadata = $metadata;
		$this->db->setSortColumn( 'nodeid' );

		$this->current = True;

		$this->write();
	}

	/**
	 * Writes the inventory data out to the file
	 * @since 06/11/2013
	 */
	private function write(){

		$lock = new lock( $this->inventoryFile.'.extradata' );

		// Check if locked, and if not, set the lock
		// and rewrite the file with the new inventory.
		// Otherwise, update the live instance only
		if ( !$lock->isLocked() ){

			$lock->lock();

			$inventory = fopen( $this->inventoryFile.'.extradata', 'w');

			fwrite( $inventory, json_encode($this->metadata, True) );
			fclose( $inventory );

			$lock->unlock();

		}

		$this->db->commit();


	}
}

?>
