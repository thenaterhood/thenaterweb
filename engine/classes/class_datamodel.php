<?php

include_once GNAT_ROOT.'/lib/extern/Database.php';

class DataModel{

	private $container;
	
	public static function get( $by, $value ){

		Database::initialize();
		$queryResult = Database::select( self::tableName(), '*', array('where'=>array( $by=>$value), 'singleRow'=>True ) );

		return self::newFromAssoc( $queryResult );


	}

	public static function all(){

		Database::initialize();

		$queryResult = Database::select( self::tableName(), '*' );
		$objects = array();

		foreach ($queryResult as $row) {
			$objects[] = self::newFromAssoc( $row );
		}

		return $objects;


	}

	public static function filter( $criteria ){

		Database::initialize();

		$queryResult = Database::select( self::tableName(), '*', array('where'=>$criteria) );

		foreach ($queryResult as $row) {
			$objects[] = self::newFromAssoc( $row );
		}

		return $objects;



	}

	public static function getAttribute( $name ){

		Database::initialize();

		return Database::select( self::tableName(), $name, array('fetchStyle'=>'singleColumn') );


	}

	public function update(){

		Database::initialize();

		if ( isset($this->container) ){
			Database::update( $this->tableName(), $this->container, $this->id );
		} else {
			throw new Exception('This is not a populated model instance');

		}


	}

	public function delete(){

		Database::initialize();

		if ( isset($this->container) ){
			Database::delete( $this->tableName(), array($this->container) );
		} else {
			throw new Exception('This is not a populated model instance');
		}


	}

	public function save(){

		$this->update();
	}

	public function newFromAssoc( $container ){

		$modelName = self::modelName();

		$new = new $modelName();
		$new->setFromArray( $container );
		return $new;

	}

	public function setFromArray( $container ){

		$this->id = $container['id'];
		unset( $container['id'] );
		$this->container = $container;
	}

	private static function modelName(){

		return get_called_class();

	}

	private static function tableName(){

		return "nw_" . strtolower( get_called_class() );

	}

	public function __get( $field ){

		if ( array_key_exists($field, $this->container) ){
			return $this->container[$field];
		}

		else if ( $field == 'id' ) {

			return $this->id;

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