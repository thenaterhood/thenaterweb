<?php
include GNAT_ROOT.'/lib/core_auth.php';

$admSession = request::get_sanitized_as_object( array( 'blogid', 'postid', 'isnew' ) );

class webadmin extends controllerBase{

	private $id;
	private $configFile;

	public function __construct(){

		$this->pageData = array();

		$configFile = WEBADMIN_ROOT.'/webadmin.conf.xml';
		$this->readConfig( $configFile );

	}

	public function __call( $method, $args ){

		authenticate_user( getConfigOption('site_domain').'/webadmin' );

		$this->pageData['session'] = request::get_sanitized_as_object( array('name', 'track', 'konami', 'id', 'tag', 'type', 'node', 'start', 'end') );
		$this->pageData['content'] = pullContent( $this->page_directory.'/page_'.$method );
		$this->pageData['content'] = $content;
		$this->pageData['id'] = $content->title;
		$this->pageData['title'] = $this->title;
		$this->pageData['tagline'] = $this->catchline;
		$this->pageData['appid'] = $this->id;

		$pageData = $this->pageData;

		include $this->settings['template'];

	}


}

?>
