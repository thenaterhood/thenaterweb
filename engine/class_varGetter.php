<?php
/**
 * Provides a means of searching for and retrieving
 * variables.
 * @author Nate Levesque <public@thenaterhood.com>
 */

/**
 * Include the sanitation class
 */
include_once 'class_sanitation.php';

/**
 * Provides an interface for retrieving variables using a specific
 * method or by searching through each method to find a variable.
 */
class varGetter extends sanitation{
	 
	 /**
	  * Constructs an instance of the class and finds the variable
	  * 
	  * @param $name - the name of the variable
	  */	 
	 public function __construct( $name ){
		 
		 if ( ! $length ){ // If no length specified, find the default
			 $conf = getConfigOption( $name );
			 $length = $conf[1];
		 }
		 if ( ! $length ){ // If still no length, default to 50
			 $length = 50;
		 }
		 
		 $this->length = $length;		 

		 if ( ! $method ){ // If no method is specified, try all of them
			 $methods = array( 'post', 'get', 'cookie', 'fallback' );
			 $i = 0;
			 while ( $i < count( $methods ) and $this->dirty == null ){
				 $this->$methods[$i]($name);
				 $i++;
			 }
		 }
		 else{
			 $this->$method( $name );
		 }
		 
		 
	 }
	 
	 /**
	  * Retrieves a variable via post
	  * @param $name - the name of the variable
	  */
	 private function post( $name ){

	 	if ( in_array($name, $_POST) )
			$this->dirty = $_POST[ $name ];
	 }
	 
	 /**
	  * Retrieves a variable via get
	  * @param $name - the name of the variable
	  */
	 private function get( $name ){
	 	if ( in_array($name, $_GET) )
			$this->dirty = $_GET[ $name ];
	 }
	 
	 /**
	  * Retrieves a variable via a cookie
	  * @param $name - the name of the variable
	  */
	 private function cookie( $name ){
	 	if ( in_array($name, $_COOKIE) )
			$this->dirty = $_COOKIE[ $name ]; 
	 }
	 
	 /**
	  * Retrieves a variable via the default
	  * @param $name - the name of the variable
	  */
	 private function fallback( $name ){
		 $conf = getConfigOption( $name );
		 $this->dirty = $conf[0];
	 }
	 
 }
 ?>