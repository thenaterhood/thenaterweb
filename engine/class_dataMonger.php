<?php
/**
 * Provides an abstract class to define a standard way
 * of managing data for the classes that are managing
 * things like session data, posts, etc.
 * 
 * @since 4/13/2013
 */
 abstract class dataMonger{
	 
	 /**
	  * @var $container - a container for data, preferrably assoc array
	  */
	 protected $container;
	 
	 /**
	  * Returns the contents of the container
	  */
	 public function dump(){
		 
		 return $this->container;
		 
	 }
	 
	 /**
	  * Retrieves a field from the container, assuming the container
	  * is an associative array.
	  * 
	  * @param $name - the item to retrieve
	  */	 
	 public function __get( $name ){
		 
		 return $this->container[$name];
		 
	 }
	 
	 /**
	  * Produces a json encoded representation of the data
	  * contained in the class.
	  */
	 public function json(){
		 
		 return json_encode( $this->container );
	 }	 
 }
 ?>