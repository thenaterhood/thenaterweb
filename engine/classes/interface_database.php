<?php

interface database{

	public function quickQuery( $queryString );

	public function query( $queryString, $values );

	public function close();

	public function open();

	public function commit();

	public function selectColumn( $column, $table );

	#public function fieldStatistics();

}

?>