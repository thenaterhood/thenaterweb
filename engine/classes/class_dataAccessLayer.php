<?php
include_once GNAT_ROOT.'/lib/extern/Database.php';
include_once GNAT_ROOT.'/lib/classes/class_modelBase';

class DataAccessLayer{
	
	private $tables;
	private $models;
	private $mode;
	private $source;
	private $usedb;
	private $usefile;

	public function __construct(){

		$this->tables = array();
		$this->models = array();
		$this->mode = 'rw';
		$this->usedb = getConfigOption('use_db');
		$this->usefile = ! getConfigOption('use_db');

		if ( $this->usedb )
			Database::initialize();


	}

	public function registerModel( $modelName, $columns, $createTable=True ){

		$this->models[] = $modelName;

		ksort($columns);

		$this->tables[ $this->getTableName( $modelName ) ] = $columns;

		if ( $createTable ){
			$this->createTable( $this->getTableName( $modelName ), $columns );
		}


	}

	public function getAll( $modelName ){

		if ( $this->usedb ){

			$queryResult = Database::select( $this->getTableName( $modelName ), '*', array( ) );
			$objects = array();

			foreach ($queryResult as $row) {
				$objects[] = $modelName::fromArray( $row );
			}

		}

		if ( $this->usefile ){

			// TODO: implement caching so this can work reasonably
		}

		return $objects;

	}

	public function get( $modelName, $by, $value ){

		if ( $this->usedb ){

			$queryResult = Database::select( $this->getTableName( $modelName ), '*', array('where'=>array( $by=>$value), 'singleRow'=>True ) );

			return $modelName::fromArray($row);

		}

		if ( $this->usefile ){

			// TODO: implement caching so this can work reasonably

		}


	}

	public function getAttributeValues( $modelName, $attrName ){

		return Database::select( $this->getTableName( $modelName ), $attrName, array('fetchStyle'=>'singleColumn') );

	}

	public function filter( $modelName, $criteria ){

		$queryResult = Database::select( $this->getTableName( $modelName ), '*', array('where'=>$criteria) );

		foreach ($queryResult as $row) {
			$objects[] = $modelName::fromArray( $row );
		}

		return $objects;



	}

	public function search( $modelName, $criteria ){

		// TODO: implement actual search-like features

		return $this->filter( $modelName, $criteria );


	}

	public static function save( $modelName, $object ){


		if ( isset($object['id']) ){
			Database::update( self::getTableName( $modelName ), $object->container, $object->id );
		} else {
			throw new Exception('This is not a populated model instance');

		}


	}

	public static function delete( $modelName, $criteria ){

		if ( isset($criteria['id']) ){
			Database::delete( self::getTableName( $modelName ), $criteria );
		} else {
			throw new Exception('This is not a populated model instance');
		}


	}

	public function createRegisteredTables(){

		foreach ($this->tables as $name => $columns) {
			$this->createTable( $name, $columns );
		}
	}

	private function createTable( $name, $columns ){

		if ( $this->usedb ){

			$query = 'CREATE TABLE IF NOT EXISTS' . $name . '( ';

			foreach ($columns as $name => $type) {
				
				$query = $query . $name . ' ' . $type . ',';

			}

			if ( getConfigOption('engine_storage_db') == 'sqlite' ){
				$query = $query . 'id INTEGER PRIMARY KEY );';
			} else if ( getConfigOption('engine_storage_db') == 'pgsql' ){
				$query = $query . 'id SERIAL );';
			} else {
				$query = $query . 'id INTEGER AUTO_INCREMENT PRIMARY KEY );';
			}

			Database::sql( $query );

		}

		if ( $this->usefile ){

			mkdir( 'site-data/' . $name );

		}



	}

	private function buildCache( $modelName, $data ){


	}

	private function readCache( $modelName, $data ){


	}

	private static function getTableName( $modelName ){


		return 'naterweb_' . strtolower( $modelName ) .'s';

	}

	private function getCacheName( $modelName, $data ){


	}

	private function loadFile( $filename ){


	}
}

?>