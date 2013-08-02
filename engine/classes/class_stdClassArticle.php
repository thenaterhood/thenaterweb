<?php

include_once GNAT_ROOT.'/classes/class_article.php';

class stdClassArticle extends article{
	
	function __construct( $stdClass ){

		$this->container['title'] = $stdClass->title;
		$this->container['tags'] = $stdClass->tags;
		$this->container['link'] = $stdClass->link;
		$this->container['content'] = $stdClass->content;
		$this->container['datestamp'] = $stdClass->datestamp;
		$this->container['date'] = $stdClass->date;

	}

}

?>