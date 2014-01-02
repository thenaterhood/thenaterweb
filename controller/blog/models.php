<?php

include_once GNAT_ROOT.'/classes/class_mappedArticle.php';

class Blogpost extends ModelBase{


	public function __construct(){

		$this->fields = array(
		'tags'		=> Model::TextField(), 
		'content'	=> Model::TextField(), 
		'title'		=> Model::CharField( array('length'=>255) ), 
		'date'		=> Model::TextField(), 
		'datestamp'	=> Model::TextField(), 
		'updated'	=> Model::TextField(),
		'nodeid'	=> Model::TextField()
		);


	}

	public function getArticle( $bloguri='blog' ){

		$asArray = $this->as_array();

		$article = new mappedArticle( $asArray, True );
		$article->link = $bloguri.'/read/'.$article->nodeid.'.htm';
		$article->date = date( "F j, Y, g:i a", strtotime($asArray['datestamp']) );
		return $article;
	}

}

?>