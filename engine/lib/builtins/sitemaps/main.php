<?php
include 'engine/lib/core_sitemap.php';

$session = request::get_sanitized_as_object( array('regen', 'type', 'page') );

class sitemaps extends ControllerBase{

	public function __construct(){

		$this->pageData = array();
		$this->settings = array();
	}

	public function __call( $method, $args ){
            
                $app = engine::get_app($method);
            
		$appRoot = $app['root'];
                $name = $app['name'];
                
		$session = request::get_sanitized_as_object( array('type') );

		if ( file_exists($appRoot.'/main.php') ){
			include $appRoot.'/main.php';
			define( strtoupper($name).'_ROOT', $appRoot );
			
			$blogdef = new $name();

			$sitemap = createSitemap( $blogdef->getPageList() );

			if ( $session->type == "html" ){
				print $sitemap->toHtml();
			}
			else{
				Header('Content-Type: text/xml');
				print $sitemap->toXml();
			}
		} else {
			echo 'Sitemap not found.';
		}

	}

}

?>