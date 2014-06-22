<?php

namespace Content\Loaders;

require_once(NWEB_ROOT.'/classes/ContentLoaders/interface_contentLoader.php');

class PhpLoader implements ContentLoader{

	private $page_modification;
	private $page_filename;
	private $title;

	private static $type = 'html';

	public function __construct($file){

		$this->page_filename     = $file;
		$this->page_modification = filemtime($file);

	}

	public function __get($property){

		if (property_exists($this, $property)){
			return $this->$property;
		}
	}


	public function getType(){
		return $this->type;
	}

	public function setTitle($title){
		$this->title = $title;
	}

	public function render_html( $context=null ){
		$page=$context;
		include $this->page_filename;
	}

	public function render_atom($context=null){
		echo '';
	}

	public function render_rss($context=null){
		echo '';
	}

	public function getMetadata(){
		return array('mtime'=>$this->page_modification);
	}



}