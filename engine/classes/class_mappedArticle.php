<?php

include_once $_SERVER['DOCUMENT_ROOT'].'/engine/classes/class_article.php';

class mappedArticle extends article{
	
	function __construct( $map ){

		$this->container = $map;

	}

}

?>