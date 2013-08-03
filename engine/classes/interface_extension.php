<?php


interface Extension{


	public function __construct( $session );

	public function getPrefaceCode();

	public function getPostCode();

}




?>