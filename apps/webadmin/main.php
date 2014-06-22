<?php
include NWEB_ROOT.'/lib/core_auth.php';
include_once NWEB_ROOT.'/lib/builtins/auth/models.php';


class webadmin extends ControllerBase{

	private $id;
	private $configFile;

	public function __construct(){

		$this->pageData = array();

		$configFile = WEBADMIN_ROOT.'/webadmin.conf.xml';
		$this->readConfig( $configFile );
		$this->settings['page_directory'] = WEBADMIN_ROOT.'/pages';


	}

	public function __call( $method, $args ){

		$isAuthed = auth_user( getConfigOption('site_domain').'/webadmin', 'nwadmin' );

		if ( $isAuthed ){
			$this->pageData['session'] = request::get_sanitized_as_object( 
				array('name', 'track', 'konami', 'id', 'tag', 'type', 'node', 'start', 'end') );
			$this->pageData['content'] = pullContent( WEBADMIN_ROOT.'/pages/page_'.$method.'.php' );

			$admSession = request::get_sanitized_as_object( array( 'blogid', 'postid', 'isnew' ) );

			$this->pageData['id'] = $this->pageData['content']->title;
			$this->pageData['title'] = $this->title;
			$this->pageData['tagline'] = $this->catchline;
			$this->pageData['appid'] = $this->id;
			$this->pageData['static'] = WEBADMIN_ROOT.'/pages';

			$pageData = $this->pageData;

			render_php_template( $this->settings['template'], $pageData );
		} else {
                    $this->unauthorized();
                }

	}

	public function home(){

		$isAuthed = auth_user( getConfigOption('site_domain').'/webadmin', 'nwadmin' );

		if ( $isAuthed ){
			$this->pageData['session'] = request::get_sanitized_as_object( 
				array('name', 'track', 'konami', 'id', 'tag', 'type', 'node', 'start', 'end') );
			$this->pageData['content'] = pullContent( WEBADMIN_ROOT.'/pages/page_home.php' );

			$this->pageData['title'] = $this->title;
			$this->pageData['tagline'] = $this->catchline;
			$this->pageData['appid'] = $this->id;
			$this->pageData['apps'] = load_all_applications();
			$this->pageData['static'] = WEBADMIN_ROOT.'/pages';


			$pageData = $this->pageData;

			render_php_template( $this->settings['template'], $pageData );


		} else {
                    $this->unauthorized();
                }

	}


}

?>
