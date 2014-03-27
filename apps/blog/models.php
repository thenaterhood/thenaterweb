<?php

include_once NWEB_ROOT.'/classes/class_mappedArticle.php';

class Blogpost extends ModelBase{


	public function __construct(){

		$this->addfield( 'tags', 		Model::TextField() );
		$this->addfield( 'content', 	Model::TextField() );
		$this->addfield( 'title', 		Model::CharField( array('length'=>255) ) );
		$this->addfield( 'date', 		Model::TextField() );
		$this->addfield( 'datestamp', 	Model::TextField() );
		$this->addfield( 'updated', 	Model::TextField() );
		$this->addfield( 'nodeid', 		Model::TextField() );
		$this->addfield( 'tag',			Model::ManyToMany( array('related_name'=>'post_tags', 'model'=>'PostTag' ) ) );
		$this->addfield( 'author',		Model::ForeignKey( array('related_name'=>'author', 'model'=>'User') ) );


	}

	public function getArticle( $bloguri='blog' ){

		$asArray = $this->as_array();

		$article = new MappedArticle( $asArray, True );
		$article->link = getConfigOption('site_domain').'/'.$bloguri.'/read/'.$article->nodeid.'.htm';
		$article->date = date( "F j, Y, g:i a", strtotime($asArray['datestamp']) );
		return $article;
	}

}

class PostTag{


}

?>