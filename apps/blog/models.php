<?php


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

}

class PostTag{


}

?>