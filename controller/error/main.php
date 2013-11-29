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
		$this->pageData['content'] = pullContent( array( $this->page_directory.'/page_'.$session->id, $this->page_directory.'/hidden_'.$session->id, GNAT_ROOT.'/lib/pages/page_'.$session->id ) );
		$this->pageData['id'] = $content->title;
		$this->pageData['title'] = $this->title;
		$this->pageData['tagline'] = $this->catchline;
		$this->pageData['appid'] = $this->id;


		$errorSession = new session( array( 'type' ) );
		$type = "err404";
		if ( $errorSession->type != '' )
			$type = "err".$errorSession->type;
		
		$this->$type();


	}

	private function err404(){
		$_GET['id'] = '404';
		header('HTTP/1.0 404 Not Found');
		
	}

	private function err403(){
		$_GET['id'] = '403';
		header('HTTP/1.0 403 Forbidden');

	}


}

?>
