<?php

class error extends controllerBase{

	private $id;
	private $configFile;

	public function __construct(){

		$this->pageData = array();
		
		$configFile = ERROR_ROOT.'/error.conf.xml';
		$this->readConfig( $configFile );

		$session = request::get_sanitized_as_object( array('name', 'track', 'konami', 'id') );


		$this->pageData['session'] = $session;
		$this->pageData['static'] = $this->page_directory;
		$content = pullContent( array( $this->page_directory.'/page_'.$session->id, $this->page_directory.'/hidden_'.$session->id ) );
		$this->pageData['content'] = $content;
		$this->pageData['id'] = $content->title;
		$this->pageData['title'] = $this->title;
		$this->pageData['tagline'] = $this->catchline;
		$this->pageData['appid'] = $this->id;


		$this->err404();


	}

	private function err404(){
		header('HTTP/1.0 404 Not Found');
		
	}

	private function err403(){
		header('HTTP/1.0 403 Forbidden');

	}


}

?>
