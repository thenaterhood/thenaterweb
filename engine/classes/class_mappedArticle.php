<?php

include_once GNAT_ROOT.'/classes/class_article.php';

class mappedArticle extends article{
	
	function __construct( $map ){

		$this->container = $map;

	}

}

?>