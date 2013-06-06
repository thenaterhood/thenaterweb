<?php

include_once 'class_article.php';

class postWriter extends article{

	private $nodefile;

	function __construct( $title, $tags, $content, $datestamp, $date, $bloguri, $nodefile ){

		$this->container['title'] = $title;
		$this->container['date'] = $date;
		$this->container['datestamp'] = $datestamp;
		$this->container['bloguri'] = '/'.$bloguri;
		$this->container['content'] = explode("\n", $content);
		$this->container['tags'] = $tags;

		$this->nodefile = $nodefile;

	}

	public function write(){

		$articleJson = $this->json();

		$nodefile = fopen( $this->nodefile, 'w' );
		fwrite( $nodefile, $articleJson );
		fclose( $nodefile );

	}

}

?>