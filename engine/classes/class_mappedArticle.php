<?php

include_once 'class_article.php';

class mappedArticle extends article{
	
	function __construct( $map ){

		$this->container = $map;

	}

}

?>