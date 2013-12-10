<?php
include GNAT_ROOT.'/lib/core_auth.php';


class webadmin extends controllerBase{

	private $id;
	private $configFile;

	public function __construct(){

		$this->pageData = array();

		$configFile = WEBADMIN_ROOT.'/webadmin.conf.xml';
		$this->readConfig( $configFile );

	}

	public function __call( $method, $args ){

		$isAuthed = auth_user( getConfigOption('site_domain').'/webadmin' );

		if ( $isAuthed ){
			$this->pageData['session'] = request::get_sanitized_as_object( 
				array('name', 'track', 'konami', 'id', 'tag', 'type', 'node', 'start', 'end') );
			$this->pageData['content'] = pullContent( $this->page_directory.'/page_'.$method );

			$admSession = request::get_sanitized_as_object( array( 'blogid', 'postid', 'isnew' ) );

			$this->pageData['id'] = $content->title;
			$this->pageData['title'] = $this->title;
			$this->pageData['tagline'] = $this->catchline;
			$this->pageData['appid'] = $this->id;

			$pageData = $this->pageData;

			include $this->settings['template'];
		}

	}

	public function home(){

		$isAuthed = auth_user( getConfigOption('site_domain').'/webadmin' );

		if ( $isAuthed ){
			$this->pageData['session'] = request::get_sanitized_as_object( 
				array('name', 'track', 'konami', 'id', 'tag', 'type', 'node', 'start', 'end') );
			$this->pageData['content'] = pullContent( $this->page_directory.'/page_'.$method );

			$admSession = request::get_sanitized_as_object( array( 'blogid', 'postid', 'isnew' ) );

			$this->pageData['id'] = $content->title;
			$this->pageData['title'] = $this->title;
			$this->pageData['tagline'] = $this->catchline;
			$this->pageData['appid'] = $this->id;
			$this->pageData['apps'] = load_all_applications();

			$pageData = $this->pageData;

			include $this->settings['template'];


		}

	}


}

?>
