<?php
include_once NWEB_ROOT.'/lib/core_auth.php';

use Naterweb\Content\Loaders\ContentFactory;
use Naterweb\Content\Renderers\PhpRenderer;
use Naterweb\Client\request;

class page extends ControllerBase{

	private $id;
	private $configFile;
	private $renderer;

	public function __construct(){


		$approot = PAGE_ROOT;
		$configFile = $approot.'/mainsite.conf.xml';
		$this->readConfig( $configFile );
		$this->renderer = new PhpRenderer($this->template);

		$session = request::get_sanitized_as_object( array('name', 'track', 'konami', 'id') );

		$this->settings['approot'] = $approot;
		$this->renderer->set_value('session', $session);
		$this->renderer->set_value('static', $this->page_directory);

		if ( file_exists($this->page_directory.'/page_'.$session->id.'.html') ){
			$content = ContentFactory::loadContentFile($this->page_directory.'/page_'.$session->id.'.html');
		} elseif ( file_exists($this->page_directory.'/hidden_'.$session->id.'.html')) {
			$content = ContentFactory::loadContentFile($this->page_directory.'/hidden_'.$session->id.'.html');
		} elseif ( file_exists($this->page_directory.'/page_'.$session->id.'.pre') ){
			$content = ContentFactory::loadContentFile($this->page_directory.'/page_'.$session->id.'.pre', 'txt');
		} elseif ( file_exists($this->page_directory.'/hidden_'.$session->id.'.pre')){
			$content = ContentFactory::loadContentFile($this->page_directory.'/hidden_'.$session->id.'.pre', 'txt');
		} elseif ( file_exists($this->page_directory.'/page_'.$session->id.'.txt') ){
			$content = ContentFactory::loadContentFile($this->page_directory.'/page_'.$session->id.'.txt', 'txt');
		} elseif ( file_exists($this->page_directory.'/hidden_'.$session->id.'.txt')){
			$content = ContentFactory::loadContentFile($this->page_directory.'/hidden_'.$session->id.'.txt', 'txt');
		} elseif ( file_exists($this->page_directory.'/page_'.$session->id.'.php') ){
			$content = ContentFactory::loadContentFile($this->page_directory.'/page_'.$session->id.'.php');
		} elseif ( file_exists($this->page_directory.'/hidden_'.$session->id.'.php')){
			$content = ContentFactory::loadContentFile($this->page_directory.'/hidden_'.$session->id.'.php');
		} else {
			throw new \Exception("Page not found.");
		}

		$content->setTitle($session->id);

		$this->renderer->set_value('content', $content);
		$this->renderer->set_value('id', $content->title);
		$this->renderer->set_value('title', $this->title);
		$this->renderer->set_value('tagline', $this->catchline);
		$this->renderer->set_value('appid', $this->id);



	}

	public function getPageList(){

		$pages = array();

		$handler = opendir( $this->page_directory );

		while( $file = readdir( $handler)){
			if ( $file != '.' && $file != '..' && substr($file, 0, 5) == 'page_' ){
				$nodeinfo = pathinfo($file);

				$url = new UrlBuilder(array(
					$this->settings['id'] => substr($nodeinfo['filename'], 5)
					));

				$pages[ $this->settings['page_directory'].'/'.$file ] = 
				     $url->build();
			}
		}

		return $pages;

	}

	public function __call( $page, $args ){

		$this->renderer->render();

	}


}

?>
