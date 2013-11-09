<?php

interface database{

	/**
	 * Allows a preconstructed query string to 
	 * be run on the database. Provides no abstraction 
	 * so in complex queries this could result in 
	 * database-type dependent queries.
	 *
	 * @param $queryString - the query to run
	 */
	public function quickQuery( $queryString );

	/**
	 * Allows the database to be queried
	 *
	 * @param $queryString - the main part of the query 
	 * with no values inserted
	 * @param $values - an array of values to be used 
	 * in the query.
	 */
	public function query( $queryString, $values );

	/**
	 * Closes the connection to the database without 
	 * committing any changes.
	 */
	public function close();

	/**
	 * Opens a connection to the database
	 */
	public function open();

	/**
	 * Commits any changes to the database.
	 */
	public function commit();

	/**
	 * Returns all values from a particular column 
	 * in a table as an array. In SQL: select x from table;
	 * 
	 * @param $column - the column to use
	 * @param $table - the table to use
	 */
	public function selectColumn( $column, $table );

	/**
	 * Selects items that have a column matching the given 
	 * value ( SQL: select x from table where x=y) and 
	 * returns an array of the matching items.
	 * 
	 * @param $column - the column to search within
	 * @param $value - the value to match
	 * @param $table - the table to search in
	 */
	public function selectSome( $column, $value, $table );

	/**
	 * Gets the count of rows in a table
	 * 
	 * @param $table - the table to use
	 */
	public function getRowCount( $table );

	/**
	 * Returns all of the data in a particular table. 
	 * Probably a bad idea to use this for large databases.
	 *
	 * @param $table - the table to return data from
	 */
	public function selectTable( $table );

	/**
	 * Drops a table from the database.
	 *
	 * @param $table - the table to drop
	 */
	public function dropTable( $table );

	/**
	 * Checks if a value exists in a database column
	 * and returns a boolean value.
	 * 
	 * @param $column - the column to search in
	 * @param $value - the value to search for
	 * @param $table - the table to search in
	 */
	public function exists( $column, $value, $table );

	/**
	 * Inserts a row into the database
	 * 
	 * @param $table - the table to insert into
	 * @param $values - the values to insert into 
	 * the table.
	 */
	public function insert( $table, $values );

}

?>