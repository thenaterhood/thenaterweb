<?php

include_once GNAT_ROOT.'/classes/class_dataAccessLayer.php';

class ModelBase{

	private $container;

	public function __construct( $array ){

		$this->$container = $array();

	}

	public function save(){


		if ( isset($this->container) ){
			DataAccessLayer::save( get_called_class(), $this->container );
		} else {
			throw new Exception('This is not a populated model instance');

		}


	}


	public function delete(){

		if ( isset($this->container) ){
			DataAccessLayer::delete( get_called_class(), array($this->container) );
		} else {
			throw new Exception('This is not a populated model instance');
		}


	}

	public static function fromArray( $array ){

		$instance = new static( $array );

		return $instance;
	}


	public function __get( $field ){

		if ( array_key_exists($field, $this->container) ){
			return $this->container[$field];
		} else {
			throw new Exception('Model field does not exist.');
		}

	}

	public function __set( $field, $value ){

		if ( array_key_exists($field, $this->container) ){

			$this->container[$field] = $value;

		} else {

			throw new Exception('Model field does not exist.');
		}
	}

}