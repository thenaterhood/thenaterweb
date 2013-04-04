<?php
/*
* Author: Nate Levesque <public@thenaterhood.com>
* Language: PHP
* Filename: core_json.php
* 
* Description:
* 	Constructs and returns an instance of a class that contains json
*	data.
*/


class jsonMaker{
	
	private $jsonData;
	
	/**
	 * Creates json data from an associative array
	 * 
	 * @param dataMap - an associative array
	 */
	function __construct( $dataMap ){
		
		$this->jsonData = json_encode( $dataMap );
	}
	
	/**
	 * Returns the json data
	 * 
	 * @param - unused
	 */
	function output(){
		
		return $this->jsonData;
	}
	
}
