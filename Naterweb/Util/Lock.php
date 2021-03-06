<?php
/**
 * Contains a class that deals with managing file locks
 */
namespace Naterweb\Util;

use Naterweb\Engine\Configuration;
/**
 * Include the config class so we can retrieve the dynamic directory
 */
include_once NWEB_ROOT.'/Engine/Configuration.php';


/**
 * Defines a class to manage locks for files
 * @since 06/05/2013
 * @author Nate Levesque <public@thenaterhood.com>
 */
class Lock{

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
	 * @var $lockTime;
	 */
	private $lockTime;

	/**
	 * Constructs and instance of the lock class
	 * @param $file - the file (full path) to create a lock for
	 */
	public function __construct( $file ){

		$this->file = $file;

		$this->lockfile = Configuration::get_option('dynamic_directory').'/'.str_replace('/', '_', $file).'.lock';
		unset( $config );

		if ( file_exists($this->lockfile) ){
			$this->hasLock = True;
			$this->readExistingLock();
		} 
		else{ 

			$this->hasLock = False;

		}

	}

    /**
     *
     */
    private function readExistingLock(){

        $lockContents = file_get_contents($this->lockfile);
        $this->lockTime = $lockContents;
        if ( ( time() - $this->lockTime ) > 120 ){
                $this->unlock();
        }
    }


	/**
	 * Returns whether or not the file has been locked
	 * @return - boolean True if a lock exists
	 */
	public function isLocked(){

		return $this->hasLock;

	}

	/**
	 * Creates a lock for the file in the dynamic directory.
	 * Note that for this to be effective, everything must
	 * actually observe locks.
	 */
	public function Lock(){

		$handle = fopen($this->lockfile, 'w');
		fwrite( $handle, time() );
		fclose( $handle );

		$this->hasLock = True;

	}

	/**
	 * Removes a lock on a file
	 */
	public function unlock(){

		unlink($this->lockfile);

		$this->hasLock = False;


	}

}

?>
