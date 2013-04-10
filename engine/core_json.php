<?php
/**
 * Constructs json data
 * 
 * @author Nate Levesque <public@thenaterhood.com>
 * Language: PHP
 * Filename: core_json.php
 * 
 */

/**
 * Constructs an array of json data from an associative array
 */
class jsonMaker{
	
	/**
	 * @var $jsonData - json data
	 */
	private $jsonData;
	
	/**
	 * Creates json data from an associative array using
	 * php's builtin json_encode function
	 * 
	 * @param dataMap - an associative array
	 */
	function __construct( $dataMap ){
		
		$this->jsonData = json_encode( $dataMap );
	}
	
	/**
	 * Returns the json data
	 * 
	 */
	function output(){
		
		return $this->jsonData;
	}
	
}
