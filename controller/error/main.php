<?php

class error extends controllerBase{

	private $id;
	private $configFile;

	public function __construct(){

		$this->pageData = array();
		
		$configFile = ERROR_ROOT.'/error.conf.xml';
		$this->readConfig( $configFile );

		$session = new session( array('name', 'track', 'konami', 'id', 'tag', 'type', 'node', 'start', 'end') );


		$this->pageData['session'] = $session;
		$this->pageData['static'] = $this->page_directory;
		$content = pullContent( array( $this->page_directory.'/page_'.$session->id, $this->page_directory.'/hidden_'.$session->id ) );
		$this->pageData['content'] = $content;
		$this->pageData['id'] = $content->title;
		$this->pageData['title'] = $this->title;
		$this->pageData['tagline'] = $this->catchline;
		$this->pageData['appid'] = $this->id;


		$type = "err404";
		if ( $session->id != '' && in_array($session->id, array( '404', '403' )));
			$type = "err".$session->id;
		
		$this->$type();


	}

	private function err404(){
		header('HTTP/1.0 404 Not Found');
		
	}

	private function err403(){
		header('HTTP/1.0 403 Forbidden');

	}


}

?>
