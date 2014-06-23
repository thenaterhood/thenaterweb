<?php
include_once NWEB_ROOT.'/lib/core_auth.php';
require_once NWEB_ROOT.'/classes/class_contentFactory.php';

class page extends ControllerBase{

	private $id;
	private $configFile;

	public function __construct(){

		$this->pageData = array();

		$approot = PAGE_ROOT;
		$configFile = $approot.'/mainsite.conf.xml';
		$this->readConfig( $configFile );

		$session = request::get_sanitized_as_object( array('name', 'track', 'konami', 'id') );

		$this->settings['approot'] = $approot;
		$this->pageData['session'] = $session;
		$this->pageData['static'] = $this->page_directory;

		if ( file_exists($this->page_directory.'/page_'.$session->id.'.html') ){
			$content = \Content\Loaders\ContentFactory::loadContentFile($this->page_directory.'/page_'.$session->id.'.html');
		} elseif ( file_exists($this->page_directory.'/hidden_'.$session->id.'.html')) {
			$content = \Content\Loaders\ContentFactory::loadContentFile($this->page_directory.'/hidden_'.$session->id.'.html');
		} elseif ( file_exists($this->page_directory.'/page_'.$session->id.'.pre') ){
			$content = \Content\Loaders\ContentFactory::loadContentFile($this->page_directory.'/page_'.$session->id.'.pre', 'txt');
		} elseif ( file_exists($this->page_directory.'/hidden_'.$session->id.'pre')){
			$content = \Content\Loaders\ContentFactory::loadContentFile($this->page_directory.'/hidden_'.$session->id.'.pre', 'txt');
		} elseif ( file_exists($this->page_directory.'/page_'.$session->id.'.txt') ){
			$content = \Content\Loaders\ContentFactory::loadContentFile($this->page_directory.'/page_'.$session->id.'.txt', 'txt');
		} elseif ( file_exists($this->page_directory.'/hidden_'.$session->id.'txt')){
			$content = \Content\Loaders\ContentFactory::loadContentFile($this->page_directory.'/hidden_'.$session->id.'.txt', 'txt');
		} elseif ( file_exists($this->page_directory.'/page_'.$session->id.'.php') ){
			$content = \Content\Loaders\ContentFactory::loadContentFile($this->page_directory.'/page_'.$session->id.'.php');
		} elseif ( file_exists($this->page_directory.'/hidden_'.$session->id.'php')){
			$content = \Content\Loaders\ContentFactory::loadContentFile($this->page_directory.'/hidden_'.$session->id.'.php');
		} else {
			throw new \Exception("Page not found.");
		}

		$content->setTitle($session->id);

		$this->pageData['content'] = $content;
		$this->pageData['id'] = $content->title;
		$this->pageData['title'] = $this->title;
		$this->pageData['tagline'] = $this->catchline;
		$this->pageData['appid'] = $this->id;



	}

	public function getPageList(){

		$pages = array();

		$handler = opendir( $this->page_directory );

		while( $file = readdir( $handler)){
			if ( $file != '.' && $file != '..' && substr($file, 0, 5) == 'page_' ){
				$nodeinfo = pathinfo($file);
				$pages[ $this->settings['page_directory'].'/'.$file ] = 
				     getConfigOption('site_domain').'/?url='.$this->settings['id'].'/'.substr($nodeinfo['filename'], 5);
			}
		}

		return $pages;

	}

	public function __call( $page, $args ){

		render_php_template( $this->template, $this->pageData );

	}


}

?>
