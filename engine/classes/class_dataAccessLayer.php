<?php
/**
 * Handles all access to data via 
 * a database. This file contains 
 * classes and functions that interact 
 * with the database and database library 
 * directly.
 * 
 * @package DatabaseAccess
 */

/**
 * Include the database library
 */
include_once NWEB_ROOT.'/lib/extern/php-database/Database.php';

/**
 * The main data access layer for managing 
 * all database interactions.
 */
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

		if ( $this->usedb ){
			Database::initialize();
		}


	}

	/**
	 * Registers a model with the data access layer. Will create the 
	 * database tables if they do not exist and will store the model 
	 * for later lookups. This function must be called for each of the 
	 * models that will be used any time a new data access layer 
	 * is initialized.
	 * 
	 * @param $modelName - the class name of the model
	 * @param $createTable - create the associated database table. Defaults 
	 *	to true, as the table will only be created if it does not already exist.
	 */
	public function registerModel( $modelName, $createTable=True ){

		$this->models[] = $modelName;
		$model = new $modelName();
		$columns = $model->getFields();

		ksort($columns);

		$this->tables[ $this->getTableName( $modelName ) ] = $columns;

		if ( $createTable ){
			$this->createTable( $this->getTableName( $modelName ), $columns );
		}


	}

	/**
	 * Registers a new model with the data access layer from an existing 
	 * instance of the model. All models must be registered with new instances 
	 * of the data access layer in order to use them with the database.
	 *
	 * @param $instance - the instance of the model to register
	 * @param $createTable - create the associated database table. Defaults to true
	 *	 as the table will only be created if it does not already exist.
	 */
	public function registerModelFromInstance( $instance, $createTable=True ){

		$this->models[] = $instance->name;
		$columns = $instance->fields;
		ksort( $columns );

		$this->tables[ $this->getTableName( $instance->name ) ] = $columns;

		if ( $createTable ){
			$this->createTable( $this->getTableName( $instance->name), $columns );
		}
	}

	/**
	 * Retrieve all of the instances of a model from the database
	 *
	 * @param $modelName - the name of the model class to retrieve. Must 
	 *	already be registered with the data access layer.
	 *
	 * @return $objects - an array of model instances
	 */
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

	/**
	 * Retrieves a single model from the database given a criteria.
	 *
	 * @param $modelName - the name of the model class to retrieve. Must 
	 * 	be registered with the data access layer prior to retrieval.
	 * @param $by - the field of the model to filter by
	 * @param $value - the value of the field to search for (ideally, an id 
	 * 	or other unique value).
	 * @param $genericClass - whether to return an instance of the model class 
	 *	 or a generic class (stdClass). True results in a generic class. The generic 
	 *	 class does not have full functionality of a class extending from modelBase 
	 *	 but may be useful if a model name does not correspond to an actual table. 
	 *	 This is set to true for internally retrieving many2many and foreignkey relations.
	 *
	 * @return an instance of the model or an stdClass
	 */
	public function get( $modelName, $by, $value, $genericClass=False ){

		if ( $this->usedb ){

			$queryResult = Database::select( $this->getTableName( $modelName ), '*', array('where'=>array( $by=>$value), 'singleRow'=>True ) );

			if ( $genericClass && $queryResult ){
				return (object)$queryResult;

			}

			else if ( ! $genericClass && $queryResult ){

				return $modelName::fromArray($queryResult);

			}
			else
				return null;

		}

		if ( $this->usefile ){

			// TODO: implement caching so this can work reasonably

		}


	}

	/**
	 * Returns all of the values contained in a database column for a given
	 * table. Does not provide functionality for manipulating them, only 
	 * returns an array.
	 *
	 * @param $modelName - the name of the model to retrieve for
	 * @param $attrName - the name of the model field to retrieve values for
	 *
	 * @return array - an array of values for the field
	 */
	public function getAttributeValues( $modelName, $attrName ){

		return Database::select( $this->getTableName( $modelName ), $attrName, array('fetchStyle'=>'singleColumn') );

	}

	/**
	 * Returns an array of models that match a given criteria.
	 * 
	 * @param $modelName - the name of the model to retrieve
	 * @param $criteria - an array of values corresponding to fields 
	 *	 of the model and the values to select.
	 * @param $genericClass - whether to return an instance of the model class 
	 *	 or a generic class (stdClass). True results in a generic class. The generic 
	 *	 class does not have full functionality of a class extending from modelBase 
	 *	 but may be useful if a model name does not correspond to an actual table. 
	 *	 This is set to true for internally retrieving many2many and foreignkey relations.
	 *
	 * @return $objects - an array of models or stdClasses that match the given criteria.
	 */
	public function filter( $modelName, $criteria, $genericClass=False ){

		$queryResult = Database::select( $this->getTableName( $modelName ), '*', array('where'=>$criteria) );

		$objects = array();

		foreach ($queryResult as $row) {
			if ( $genericClass )
				$objects[] = (object)$row;
			else
				$objects[] = $modelName::fromArray( $row );
		}

		return $objects;



	}


	/**
	 * Performs a fuzzy filter based on similarities rather than 
	 * attempting to match perfectly. Not yet implemented.
	 */
	public function search( $modelName, $criteria ){

		// TODO: implement actual search-like features

		return $this->filter( $modelName, $criteria );


	}

	/**
	 * Saves or updates a model instance in the database. This is 
	 * usually not called directly but is used instead by the modelBase class 
	 * for providing the functionality of saving a model directly. It is recommended 
	 * that a model be registered with the data access layer before using this 
	 * method in order to make sure that the proper database structures are in place.
	 * This function can be called directly rather than using a model's save function 
	 * in order to save models retrieved as stdClass instances.
	 *
	 * @param $modelName - the name of the model to save
	 * @param $object - an associative array of the model's contents as field_name => value.
	 *
	 * @return int - the object id
	 */
	public static function save( $modelName, $object ){


		if ( isset($object['id']) ){
			Database::update( self::getTableName( $modelName ), $object, array('id') );
			return $object['id'];
		} else {
			Database::insert( self::getTableName( $modelName ), $object );
			return Database::lastInsertId();

		}


	}

	/**
	 * Deletes a model instance from the database. This is generally not called 
	 * directly, but is called from the delete method of a model instance. It can be 
	 * called directly in order to handle removing objects that correspond to 
	 * the data in stdClass instances. It is recommended that a model be 
	 * registered with the data access layer before using this method in order 
	 * to make sure the necessary database structures are in place.
	 *
	 * @param $modelName - the name of the model class
	 * @param $criteria - the criteria to match to delete the model from the database 
	 * 	as an associative array of field_name=>value.
	 *
	 * @throws Exception - throws an exception if the model does not contain 
	 * 	an id.
	 */
	public static function delete( $modelName, $criteria ){

		if ( isset($criteria['id']) ){
			Database::delete( self::getTableName( $modelName ), array('id'=>$criteria['id']) );
		} else {
			throw new Exception('This is not a populated instance of the model: ' . $modelName
. "\n. This exception will also occur if you are attempting to delete an instance of a model 
that does not appear to have been stored in the database.");
		}


	}

	/**
	 * Creates tables for the models registered with the data access layer.
	 * Called automatically on registering a model if not otherwise instructed, 
	 * but can also be called externally.
	 */
	public function createRegisteredTables(){

		foreach ($this->tables as $name => $columns) {
			$this->createTable( $name, $columns );
		}
	}


	/**
	 * Generates the SQL to create a table in the database 
	 * for a given model. Relies on the model using fields from 
	 * the Model class to determine field types and attributes.
	 * Does not currently support creation of join tables (which 
	 * may be more appropriately handled separately).
	 *
	 * @param $name - the name of the table to create, usually 
	 * 	generated using the getTableName function of the class.
	 * @param $columns - an associative array of the columns or fields 
	 * 	of the model. The array is expected to be an array of 
	 *	field_name => field_class_instance and is not a simple string array.
	 */
	private function createTable( $name, $columns ){

		if ( $this->usedb ){


			$query = 'CREATE TABLE IF NOT EXISTS ' . $name . '( ';

			foreach ($columns as $name => $type) {
				
				$query = $query . $name . ' ' . $type->type . ',';

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

	/**
	 * Generates a database table name for a given model name.
	 * This function is used internally to create the tables 
	 * for registered models.
	 *
	 * @param $modelName - the name of the model
	 * 
	 * @return string - the table name generated.
	 */
	private static function getTableName( $modelName ){


		return 'naterweb_' . strtolower( $modelName ) .'s';

	}

	private function getCacheName( $modelName, $data ){


	}

	private function loadFile( $filename ){


	}
}

?>