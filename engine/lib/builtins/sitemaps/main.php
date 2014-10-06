<?php
include 'engine/lib/core_sitemap.php';

use Naterweb\Client\request;
use Naterweb\Content\Generators\Sitemap\XmlSitemap;
use Naterweb\Content\Generators\Sitemap\HtmlSitemap;

$session = request::get_sanitized_as_object( array('regen', 'type', 'page') );

class sitemaps extends ControllerBase{

	public function __construct(){

		$this->pageData = array();
		$this->settings = array();
	}

	public function __call( $method, $args ){
            
                $app = Naterweb\Engine\Applications::get_app($method);
		$appRoot = $app['root'];
                $name = $app['name'];
                
		$session = request::get_sanitized_as_object( array('type') );

		if ( file_exists($appRoot.'/main.php') ){
			include $appRoot.'/main.php';
			define( strtoupper($name).'_ROOT', $appRoot );
			
			$blogdef = new $name();

			if ( $session->type == "html" ){
				$sitemap = new HtmlSitemap();
			}
			else{
				$sitemap = new XmlSitemap();
			}
			
			foreach ( $blogdef->getPageList() as $file => $uri ) {

				if ( !in_array( $file, \Naterweb\Engine\Configuration::get_option('hidden_files') ) ){
					$last_modified = filemtime( $file );
					$sitemap->new_item( $uri, date(DATE_ATOM, $last_modified));
				}
			} 	
			
		} else {
			echo 'Sitemap not found.';
		}
		
		$sitemap->render();
		die();

	}

}

?>
