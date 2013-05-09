<?php
include_once 'core_web.php';

class inventory{

	private $directory;
	private $current = null;
	private $inventoryFile;

	/**
	 * Constructs an instance of the class
	 * @param $directory - the directory of which the inventory is of
	 */
	public function __construct( $directory ){

		$this->directory = $directory;
		$this->inventoryFile = getConfigOption('dynamic_directory').'/'.str_replace('/', '_', $directory);

	}

	/**
	* Creates a list of files in the working directory, sorts
	* and reverses the list, and returns it.  Intended for working
	* with blog posts stored as text files with date-coded filenames
	*/
	private function getFileList(){
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

				$recorded = count(file($this->inventoryFile));
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
	
		foreach( $files as $input ){
		
			$postData = new article("$this->directory/$input");

			$item = '<li>'. $postData->list_item_output() .'</li>'."\n";
			fwrite($inventory, $item);

		}
	
		fclose($inventory);
		$this->current = True;
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