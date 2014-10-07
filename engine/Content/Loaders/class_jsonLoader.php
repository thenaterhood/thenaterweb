<?php

namespace Naterweb\Content\Loaders;

require_once(NWEB_ROOT.'/Content/Loaders/interface_contentLoader.php');

class JsonLoader implements ContentLoader{

	private $page_modification;
	private $page_filename;
	private $page_container;
	private $container;
	private static $type = 'html';

	public function __construct($file){

		$this->page_container = array();
		$this->page_filename     = $file;
		$this->page_modification = filemtime($file);
		$this->load_content();

	}

	public function __get($property){

		if ($property == 'page_content'){
			$this->load_content();
		}

		if (property_exists($this, $property)){
			return $this->$property;
		} elseif (array_key_exists($property, $this->page_container)){
			return $this->page_container[$property];
		}
	}

	private function load_content(){

		$file_contents = file_get_contents($this->page_filename);
		$this->page_container = json_decode($file_contents, true);

	}

	public function getType(){
		return $this->type;
	}

	public function setTitle($title){
		$this->page_container['title'] = $title;
	}

	public function setUri($uri){
		$this->page_container['link'] = $uri;
	}

	public function render_html( $context=null ){
		echo '<h3>' . $this->__get('title') . '</h3>' . "\n";
		echo '<h4>' . date( "F j, Y, g:i a", strtotime($this->__get('datestamp') ) ). '</h4>' . "\n";
		$content = $this->__get('content');
		if ( is_array($content) ){
			echo '<p>' . implode($this->__get('content')) . '</p>';
		} else {
			echo $content;
		}
	}

	public function render_atom($context=null){
		$r = "<entry>";
		# In order to make the feed validate, we pull the http out of the id and append it
		# statically, then urlencode the rest of the url. Otherwise, the feed does not 
		# validate.
		$r .= "<id>" . urlencode($this->__get('link')) . "</id>";
		$r .= '<link href="http://'. htmlspecialchars( substr($this->__get('link'), 7) ) .'" />';
		$r .= '<updated>'.$this->__get('datestamp').'</updated>';
		$r .= "<title>" . htmlspecialchars( $this->__get('title') ) . "</title>";
		if ( is_array($this->__get('content') ) ){
			$content = '<p>' . implode($this->__get('content')) . '</p>';
		} else {
			$content = $this->__get('content');
		}
		$r .= "<content type='html'>" . htmlspecialchars( $content, ENT_QUOTES ) . "</content>";
		$r .= "</entry>";
		echo $r;
	}

	public function render_rss($context=null){
		echo '';
	}

	public function getMetadata(){
		return array('mtime'=>$this->page_modification);
	}



}
