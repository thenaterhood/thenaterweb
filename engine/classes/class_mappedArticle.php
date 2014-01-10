<?php

include_once NWEB_ROOT.'/classes/class_article.php';

class MappedArticle extends Article{
	
	function __construct( $map, $postFormat=False ){

		$this->container = $map;
		$this->usePostFormat = $postFormat;

	}

}

?>