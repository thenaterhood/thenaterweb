<?php
/**
 * Contains a class that deals with managing file locks
 */

/**
 * Include the config class so we can retrieve the dynamic directory
 */
include_once "class_config.php";

/**
 * Defines a class to manage locks for files
 * @since 06/05/2013
 * @author Nate Levesque <public@thenaterhood.com>
 */
class lock{

	/**
	 * @var $file - the file to be locked
	 */
	private $file;
	/**
	 * @var $lockfile - the lockfile
	 */
	private $lockfile;
	/**
	 * @var $hasLock - whether the file has a lock on it
	 */
	private $hasLock;

	/**
	 * Constructs and instance of the lock class
	 * @param $file - the file (full path) to create a lock for
	 */
	public function __construct( $file ){

		$this->file = $file;

		$config = new config();
		$this->lockfile = $config->dynamic_directory.str_replace('/', '_', $file).'.lock';
		unset( $config );

		if ( file_exists($this->lockfile) ){
			$this->hasLock = True;
		} 
		else{ 

			$this->hasLock = False;

		}

	}

	/**
	 * Returns whether or not the file has been locked
	 * @return - boolean True if a lock exists
	 */
	public function isSet(){

		return $this->hasLock

	}

	/**
	 * Creates a lock for the file in the dynamic directory.
	 * Note that for this to be effective, everything must
	 * actually observe locks.
	 */
	public function set(){

		$handle = fopen($this->lockfile, 'w');
		fwrite( $handle, time() );
		fclose( $handle );

		$this->hasLock = True;

	}

	/**
	 * Removes a lock on a file
	 */
	public function unset(){

		unlink($this->lockfile);

		$this->hasLock = False;


	}

}

?>