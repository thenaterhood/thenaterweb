<?php

namespace Naterweb\Content\Loaders;

require_once(NWEB_ROOT.'/classes/ContentLoaders/interface_contentLoader.php');

class StdLoader implements ContentLoader{

	private $page_container;

	private static $type = 'std';

	public function __construct($instance){

		$this->page_container = (array)$instance;

	}

	public function __get($property){

		if (property_exists($this, $property)){
			return $this->$property;
		} elseif (array_key_exists($property, $this->page_container)){
			return $this->page_container[$property];
		}
	}

	public function getType(){
		return $this->type;
	}

	public function setTitle($title){
		$this->container['title'] = $title;
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
		$r .= "<id>http://" . urlencode( $this->__get('link') ) . "</id>";
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
		return $r;
	}

	public function render_rss($context=null){
		$r = "<item>";
		$r .= "<title>" . $this->__get('title') ."</title>";
		$r .= "<link>" . $this->__get('link') . "</link>";
		# Produces a "description" by taking the first 100 characters of the content
		if ( is_array($this->__get('content') ) ){
			$content = '<p>' . implode($this->__get('content')) . '</p>';
		} else {
			$content = $this->__get('content');
		}
		$r .= "<description>" . substr( htmlspecialchars( $content, ENT_QUOTES ), 0, 100 ) . "...</description>";
		$r .= "</item>";
		
		return $r;
	}

	public function getMetadata(){
		return array('mtime'=>$this->page_modification);
	}



}
