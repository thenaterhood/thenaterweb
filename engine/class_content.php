<?php

include_once 'class_dataMonger.php';
/**
* Contains everything to do with retrieving and outputting
* posts in multiple forms.  Is capable of retrieving posts stored
* in .json format (preferred when available) as well as plaintext
* (file syntax described below in constructor).
* 
* Contains functions to output the post data in html format
* for displaying to a page, and atom format for use in generating
* an atom feed.
*/
class content extends dataMonger{
	
	/**
	 * Reads and parses a post file and creates an instance
	 * of the class with the post data. Capable of managing
	 * posts in json and plaintext, but prefers json if
	 * a json file exists for the requested post.
	 * 
	 * @param nodefile (string) - a yyyy.mm.dd string of a nodefile
	 */
	public function __construct($pageid, $session){

		/* Handles the case where the post file does not exist
		 * at all by pre-setting all the fields to a failure state.
		 * This also safely handles any case where the data in a post
		 * doesn't contain all of the expected fields in a typical way.
		 */
		$this->container['title'] = $pageid;
		$this->container['contentfile'] = getConfigOption('webcore_root').'/template_error.php';
		$this->container['type'] = 'php';
		$this->container['session'] = $session;

		$filename = getConfigOption('webcore_root')."/page_$pageid";
			
		if ( file_exists( $filename.'.html' ) ){
			$this->container['contentfile'] = $filename.'.html';
			$this->container['type'] = 'html';

		}
		else if ( file_exists($filename.'.php') ){
			$this->container['contentfile'] = $filename.'.php';
			$this->container['type'] = 'php';
		}
		else if ( file_exists( $filename.'.pre' ) ){
			$this->container['contentfile'] = $filename.'.pre';
			$this->container['type'] = 'pre';
		}

	}

	private function parseFile(){



	}
	
	/**
	 * Returns a representation of the post in the format requested
	 * 
	 * @param $type - the type of feed
	 */
	public function output(){
		
		return $this->container['type']();
	}

	public function display(){

		$type = $this->container['type'];

		$this->$type();
	}

	private function php(){

		$session = $this->container['session'];
		include $this->container['contentfile'];

	}

	private function html(){
		$session = $this->container['session'];
		include $this->container['contentfile'];

	}

	private function pre(){

		print '<pre>';
		include $this->container['contentfile'];
		print '</pre>';

	}

 }
 ?>