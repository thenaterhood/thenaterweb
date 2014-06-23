<?php

include_once NWEB_ROOT.'/classes/class_lock.php';

class File{

	private $name;
	private $type;
	private $lock;
	private $contents;
	private $saveMethod;

	public function __construct( $name, $type=null ){

		$this->name = $name;
		$this->type = $type;

		$this->saveMethod = 'save_plaintext';
		$this->lock = new Lock( $name );

		$this->contents = file_get_contents($this->name);

	}

	public function get_contents(){
		return $this->contents;
	}

	public function read_as_json(){

		$this->type = "json";
		$this->saveMethod = 'save_json';


		$jsoncontents = $this->get_contents();
			
		return json_decode($jsoncontents, True);
	}

	public function searchForFile(){


	}

	public function getLock(){
		if ( ! $this->lock->isLocked() ){

			$this->lock->lock();
			return True;

		} else {
			return False;
		}
	}


	public function clear(){

		$this->contents = '';


	}

	public function delete(){

		if ( $this->getLock ){
			unlink($this->name);
			return True;
		} else {
			return False;
		}

	}

	public function append( $string ){

		$this->contents = $this->contents . "\n" . $string;

	}

	public function overwrite( $string ){

		$this->contents = $string;


	}

	private function save_json(){

	}

	public function save(){

		$saver = $this->saveMethod;

		$this->$saver();

	}


	public function __destroy(){

		$this->lock->unlock();
	}
	

}


?>