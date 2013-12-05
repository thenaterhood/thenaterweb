<?php

class page extends controllerBase{

	private $id;
	private $configFile;

	public function __construct(){

		$this->pageData = array();


		$configFile = PAGE_ROOT.'/mainsite.conf.xml';
		$this->readConfig( $configFile );

		$session = new session( array('name', 'track', 'konami', 'id') );


		$this->pageData['session'] = $session;
		$this->pageData['static'] = $this->page_directory;
		$content = pullContent( array( $this->page_directory.'/page_'.$session->id, $this->page_directory.'/hidden_'.$session->id ) );
		$this->pageData['content'] = $content;
		$this->pageData['id'] = $content->title;
		$this->pageData['title'] = $this->title;
		$this->pageData['tagline'] = $this->catchline;
		$this->pageData['appid'] = $this->id;



	}


}

?>