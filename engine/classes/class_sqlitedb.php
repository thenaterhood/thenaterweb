<?php

include_once GNAT_ROOT.'/classes/interface_database.php';

class SqliteDb implements database{

	private $dbFile;
	private $sqldb;

	public function __construct( $db ){

		$this->dbFile = $db;
		$this->open();


	}

	/**
	 * Allows a preconstructed query string to 
	 * be run on the database. Provides no abstraction 
	 * so in complex queries this could result in 
	 * database-type dependent queries.
	 *
	 * @param $queryString - the query to run
	 */
	public function quickQuery( $queryString ){



	}

	/**
	 * Allows the database to be queried
	 *
	 * @param $queryString - the main part of the query 
	 * with no values inserted
	 * @param $values - an array of values to be used 
	 * in the query.
	 */
	public function query( $queryString, $values ){

		$sql = $this->sqldb->prepare( $queryString );

		foreach ($values as $i => $value) {

			$sql->bindValue( ":$i", $value );

		}

		$queryResult = $sql->execute();

		return $this->resultToArray( $queryResult );

	}

	/**
	 * Closes the connection to the database without 
	 * committing any changes.
	 */
	public function close(){

		$this->sqldb->close();
	}

	/**
	 * Opens a connection to the database
	 */
	public function open(){

		$this->sqldb = new SQLite3($this->dbFile);
	}

	/**
	 * Commits any changes to the database.
	 */
	public function commit(){

		// Apparently unnecessary for the method
		// we're using to interact with SQLite3 here.

	}

	/**
	 * Returns all values from a particular column 
	 * in a table as an array. In SQL: select x from table;
	 * 
	 * @param $column - the column to use
	 * @param $table - the table to use
	 */
	public function selectColumn( $column, $table ){

		$sql = $this->sqldb->prepare( 'SELECT ' . $column . ' FROM ' . $table );
		$result = $sql->execute();

		return $this->resultToArray( $result );

	}

	/**
	 * Selects items that have a column matching the given 
	 * value ( SQL: select x from table where x=y) and 
	 * returns an array of the matching items.
	 * 
	 * @param $column - the column to search within
	 * @param $value - the value to match
	 * @param $table - the table to search in
	 */
	public function selectSome( $column, $value, $table ){

		$sql = $this->sqldb->prepare( 'SELECT * FROM ' . $table . ' WHERE ' . $column .'=:colVal' );
		$sql->bindValue( ':colVal', $value );

		$result = $sql->execute();

		return $this->resultToArray( $result );

	}

	private function resultToArray( $queryResult ){

		$resultArray = array();


		while( $result = $queryResult->fetchArray(SQLITE3_ASSOC) ){
			$resultArray[] = $result;
		}

		return $resultArray;

	}

	/**
	 * Gets the count of rows in a table
	 * 
	 * @param $table - the table to use
	 */
	public function getRowCount( $table ){

		$queryResult = $this->sqldb->query( 'SELECT * FROM '. $table );

		return $queryResult->numRows();

	}

	/**
	 * Returns all of the data in a particular table. 
	 * Probably a bad idea to use this for large databases.
	 *
	 * @param $table - the table to return data from
	 */
	public function selectTable( $table ){

		$queryResult = $this->sqldb->query( 'SELECT * FROM '. $table );

		return $this->resultToArray( $queryResult );

	}

	/**
	 * Drops a table from the database.
	 *
	 * @param $table - the table to drop
	 */
	public function dropTable( $table ){

		$sql = 'DROP TABLE ' . $table;
		$this->sqldb->query( $sql );

	}

	/**
	 * Checks if a value exists in a database column
	 * and returns a boolean value.
	 * 
	 * @param $column - the column to search in
	 * @param $value - the value to search for
	 * @param $table - the table to search in
	 */
	public function exists( $column, $value, $table ){

		return count( $this->selectSome( $column, $value, $table ) ) > 0;

	}

	/**
	 * Inserts a row into the database
	 * 
	 * @param $table - the table to insert into
	 * @param $values - the values to insert into 
	 * the table.
	 */
	public function insert( $table, $values ){

		$queryString = 'INSERT INTO ' . $table . 'VALUES (';

		foreach ($values as $key => $value) {

			$queryString += ' ' . ":$key,";
		}

		$queryString = substr($queryString, 0, count($queryString)-1 ) . ' )';

		$sql = $this->db->prepare( $queryString );

		foreach ($values as $key => $value) {
			$sql->bindValue( ":$key", $value );
		}

		$sql->execute();

	}

	public function createTable( $table, $columns ){

		$queryString = 'CREATE TABLE ' . $table . ' ( id INTEGER PRIMARY KEY,';

		foreach ($columns as $name => $type ) {

			$queryString += ' ' . $name . ' ' . $type . ',';

		}

		$queryString = substr($queryString, 0, count( $queryString) - 1 ) . ' )';

		$this->sqldb->query( $queryString );

	}

}


?>