<?php
include 'engine/lib/core_sitemap.php';

$session = request::get_sanitized_as_object( array('regen', 'type', 'page') );

class sitemaps extends controllerBase{

	public function __construct(){

		$this->pageData = array();
		$this->settings = array();
	}

	public function __call( $method, $args ){

		$appRoot = 'apps/'.$method;
		$session = request::get_sanitized_as_object( array('type') );

		if ( file_exists($appRoot.'/main.php') ){
			include $appRoot.'/main.php';
			define( strtoupper($method).'_ROOT', $appRoot );
			
			$blogdef = new $method();

			$sitemap = createSitemap( $blogdef->getPageList() );

			if ( $session->type == "html" ){
				print $sitemap->toHtml();
			}
			else{
				Header('Content-Type: text/xml');
				print $sitemap->toXml();
			}
		} else {
			echo '';
		}

	}

}

?>