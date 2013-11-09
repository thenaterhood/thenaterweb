<?php

interface database{

	public function quickQuery( $queryString );

	public function query( $queryString, $values );

	public function close();

	public function open();

	public function commit();

	public function selectColumn( $column, $table );

	public function selectSome( $column, $value, $table );

	public function getRowCount( $table );

	public function selectTable( $table );

	public function dropTable( $table );

	public function exists( $column, $value, $table );

	#public function fieldStatistics();

}

?>