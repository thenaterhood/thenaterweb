<?php
include GNAT_ROOT.'/lib/core_auth.php';

$admSession = new session( array( 'blogid', 'postid', 'isnew' ) );

class webadmin extends controllerBase{

	private $id;
	private $configFile;

	public function __construct(){

		$this->pageData = array();

		$configFile = WEBADMIN_ROOT.'/webadmin.conf.xml';
		$this->readConfig( $configFile );

		$session = new session( array('name', 'track', 'konami', 'id', 'tag', 'type', 'node', 'start', 'end') );


		$this->pageData['session'] = $session;
		$this->pageData['static'] = $this->page_directory;
		$this->pageData['content'] = pullContent( array( $static.'/page_'.$session->id, $static.'/hidden_'.$session->id, GNAT_ROOT.'/lib/pages/page_'.$session->id ) );
		$this->pageData['id'] = $content->title;
		$this->pageData['title'] = $this->title;
		$this->pageData['tagline'] = $this->catchline;
		$this->pageData['appid'] = $this->id;

	}


}

?>