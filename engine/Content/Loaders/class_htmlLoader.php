<?php

namespace Naterweb\Content\Loaders;

require_once(NWEB_ROOT.'/Content/Loaders/interface_contentLoader.php');

class HtmlLoader implements ContentLoader{

	private $page_modification;
	private $page_filename;
	private $title;
	private $page_content;

	private static $type = 'html';

	public function __construct($file){

		$this->page_filename     = $file;
		$this->page_modification = filemtime($file);

	}

	public function __get($property){

		if ($property == 'page_content'){
			$this->load_content();
		}

		if (property_exists($this, $property)){
			return $this->$property;
		}
	}

	private function load_content(){

		$this->page_content = file_get_contents($this->page_filename);

	}

	public function getType(){
		return $this->type;
	}

	public function setTitle($title){
		$this->title = $title;
	}

	public function render_html( $context=null ){
		echo $this->__get('page_content');
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
