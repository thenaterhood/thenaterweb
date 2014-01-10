<?php

include NWEB_ROOT.'/lib/core_feed.php';

class feeds extends ControllerBase{

	public function __construct(){

		$this->pageData = array();
		$this->settings = array();

		$this->template = FEEDS_ROOT.'/pages/generate_feed.php';

	}

	public function __call($method, $args){
            
                $app = Engine::get_app($method);
            
		$appRoot = $app['root'];
                $name = $app['name'];

		if ( file_exists($appRoot.'/main.php') ){
			include $appRoot.'/main.php';
			define( strtoupper($name).'_ROOT', $appRoot );
			
			$blogdef = new $name();

			Header('Content-type: application/atom+xml');


			$session = request::get_sanitized_as_object( array('regen') );
			$config = new config();

			$feed = generateFeed( $blogdef, False );
			print $feed->output( getConfigOption('feed_type') );
			
		} else {
			echo 'Feed not found.';
		}

	}

}

?>
