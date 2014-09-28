<?php
include NWEB_ROOT.'/lib/core_auth.php';
include_once NWEB_ROOT.'/lib/builtins/auth/models.php';

use Naterweb\Content\Loaders\ContentFactory;
use Naterweb\Content\Renderers\PhpRenderer;
use Naterweb\Client\request;
use Naterweb\Routing\Urls\UrlBuilder;

class webadmin extends ControllerBase{

	private $id;
	private $configFile;

	public function __construct(){

		$this->pageData = array();

		$configFile = WEBADMIN_ROOT.'/webadmin.conf.xml';
		$this->readConfig( $configFile );
		$this->settings['page_directory'] = WEBADMIN_ROOT.'/pages';
		$this->id = request::sanitized_get('controller');


	}

	public function __call( $method, $args ){

		$isAuthed = auth_user( getConfigOption('site_domain').'/webadmin', 'nwadmin' );

		if ( $isAuthed ){
			$renderer = new PhpRenderer($this->settings['template']);

			$renderer->set_value('session', request::get_sanitized_as_object( 
				array('name', 'track', 'konami', 'id', 'tag', 'type', 'node', 'start', 'end') ));
			$renderer->set_value('content', ContentFactory::loadContentFile( WEBADMIN_ROOT.'/pages/page_'.$method.'.php' ));

			$admSession = request::get_sanitized_as_object( array( 'blogid', 'postid', 'isnew' ) );

			$renderer->set_value('id', $method);
			$renderer->set_value('title', $this->title);
			$renderer->set_value('tagline', $this->catchline);
			$renderer->set_value('appid', $this->id);
			$renderer->set_value('static', WEBADMIN_ROOT.'/pages');

			$urlBase = new UrlBuilder(array($this->id=>''));
			$renderer->set_value('urlBase', $urlBase->build());
			$renderer->render();

		} else {
                    $this->unauthorized();
                }

	}

	public function home(){

		$isAuthed = auth_user( getConfigOption('site_domain').'/webadmin', 'nwadmin' );

		if ( $isAuthed ){
			$renderer = new PhpRenderer($this->settings['template']);

			$renderer->set_value('session', request::get_sanitized_as_object( 
				array('name', 'track', 'konami', 'id', 'tag', 'type', 'node', 'start', 'end') ));
			$renderer->set_value('content', ContentFactory::loadContentFile( WEBADMIN_ROOT.'/pages/page_home.php' ));

			$renderer->set_value('title', $this->title);
			$renderer->set_value('tagline', $this->catchline);
			$renderer->set_value('appid', $this->id);
			$renderer->set_value('apps', load_all_applications());
			$renderer->set_value('static', WEBADMIN_ROOT.'/pages');
			$urlBase = new UrlBuilder(array($this->id=>''));
			$renderer->set_value('urlBase', $urlBase->build());
			$renderer->render();

		} else {
                    $this->unauthorized();
                }

	}


}

?>
