<?php

include_once GNAT_ROOT.'/classes/interface_database.php';
include_once GNAT_ROOT.'/classes/class_lock.php';

/**
 * A very rudimentary class for dealing with queries
 * to the json database. Supports the most basic of 
 * select, inserts, and removes. This is intended to 
 * provide very basic facilities for working with 
 * a simple database stored in a json file. This is NOT 
 * for use with huge amounts of data or often-updated 
 * data.
 */
class Query{

	private $type;
	private $table;
	private $column;
	private $rawQuery;
	private $values;

	public function __construct( $queryString, $values ){

		$this->parseQuery( $queryString, $values );


	}

	private function parseQuery( $queryString, $values ){

		$queryArray = explode(' ', $queryString );

		$this->type = strtolower( $queryArray[0] );

		if ( $this->type == 'select' ){
			# select x from table
			$this->column = $queryArray[1];
			$this->table = $queryArray[3];

		} elseif ( $this->type == 'insert' ){
			# insert into table values (x,x,x)
			$this->table = $queryArray[2];
			$this->values = $values;
		} elseif ( $this->type == 'delete' ){
			# delete x from table where id ?
			$this->table = $queryArray[3];
			$colvals = explode( '=', $queryArray[5] );
			$this->column = $colvals[0];
			$this->values = $values;
		}


	}

	public function __get( $name ){
		return $this->$name;
	}

}


/**
 * A very rudimentary database implementation stored 
 * as a json file. This is NOT to be used as anything other
 * than a very simple cache and does not support huge amounts 
 * of data or threading.
 */
class JsonDb implements database{


	private $db;

	protected $metadata;
	protected $dbData;

	public function __construct( $db ){

		$this->db = $db;
		$this->open();

	}


	public function quickQuery( $queryString ){

	}

	/**
	 * Sets the column to organize the database by. 
	 * By default, this is the integer key that is assigned 
	 * to values as they are inserted. Otherwise, the database 
	 * will be sorted according to this column.
	 */
	public function setSortColumn( $column ){
		$this->metadata['sortField'] = $column;
	}


	public function query( $queryString, $values ){

		$queryObject = new Query( $queryString, $values );

		switch ($queryObject->type) {
			case 'select':
				return $this->runSelect( $queryObject );

			case 'insert':
				return $this->runInsert( $queryObject );

			case 'remove':
				return $this->runRemove( $queryObject );
			
			default:
				break;
		}

	}

	private function runSelect( $queryObject ){

		return $this->selectColumn( $queryObject->column, '' );

	}

	private function runInsert( $queryObject ){

		$this->dbData[] = $queryObject->values;
		$this->reorganize();
		$this->metadata['last_updated'] = date(DATE_ATOM);



	}

	private function runRemove( $queryObject ){

		foreach ($this->dbData as $rowid => $row ) {

			if ( $queryObject->column = 'id' ){
				unset( $this->dbData[$rowid] );
			} elseif ( in_array( $row[ $queryObject->column ], $queryObject->values ) ){
				unset( $this->dbData[$rowid] );
			}

		}

		$this->metadata['last_updated'] = date(DATE_ATOM);

	}


	private function reorganize(){

		// Sort the multidimensional array
	    usort($this->dbData, "custom_sort");

	}

	/**
	 * Provides a custom sort function for sorting
	 * multidimensional arrays
	 */
	private function custom_sort($a,$b) {
		$field = $this->metadata['sortField'];
	    return $a[ $field ]>$b[ $field ];
	}

	public function close(){

		unset($this->metadata);
		unset($this->dbData);
	}

	public function open(){

		if ( file_exists($this->db) ){

			$jsonData = json_decode( file_get_contents($this->db, True), True );
			$this->metadata = $jsonData['metadata'];
			$this->dbData = $jsonData['data'];
		} else {
			$this->metadata = array();
			$this->dbData = array();
			$this->metadata['sortField'] = 'primary_key';
			$this->metadata['last_updated'] = date(DATE_ATOM);
		}

	}

	/**
	 * Writes the inventory data out to the file
	 * @since 06/11/2013
	 */
	private function write(){
		// Create an instance of a lock
		$lock = new lock( $this->db );

		// Check if locked, and if not, set the lock
		// and rewrite the file with the new inventory.
		// Otherwise, update the live instance only
		if ( !$lock->isLocked() ){

			$lock->lock();

			$inventory = fopen( $this->db, 'w');

			$dataMap = array();
			$dataMap['data'] = $this->dbData;
			$dataMap['metadata'] = $this->metadata;

			fwrite( $inventory, json_encode($dataMap, True) );
			fclose( $inventory );

			$lock->unlock();

		}
	}


	/**
	 * Returns an array containing the data from a 
	 * particular field, with repeats filtered out
	 *
	 * @param $column - the name of the field to access
	 * @param $table - ignored here
	 */
	public function selectColumn( $column, $table ){

		$fieldContents = array();

		foreach ( $this->dbData as $rowid => $current ) {

			if ( ! is_array( $current[$column] ) ){
				$currentField = explode( ', ', $current[$column] );
			}
			else{
				$currentField = $current[$column];
			}

			foreach ($currentField as $item) {
				if ( ! in_array($item, $fieldContents) )
					$fieldContents[] = $item;
			}

		}

		return $fieldContents;

	}

	public function exists( $column, $value, $table ){

		return count($this->selectSome( $column, $value, $table ) ) > 0;

	}

	public function selectSome( $column, $value, $table ){

		$matching = array();

		foreach ($this->dbData as $current) {

			if ( ! is_array( $current[$field] ) ){
				$currentData = explode( ', ', $current[$field] );
			}
			else{
				$currentData = $current[$field];
			}

			if ( in_array($value, $currentData) ){
				$matching[] = $current;
			}
		}

		return $matching;

	}

	public function selectTable( $table ){

		return $this->dbData;
	}

	public function getRowCount( $table ){
		return count( $this->dbData );
	}

	public function dropTable( $table ){

		$this->dbData = array();
		$this->metadata['last_updated'] = date( DATE_ATOM );
	}

	public function commit(){

		$this->write();
	}

}


?>