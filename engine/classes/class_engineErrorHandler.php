<?php

use Naterweb\Content\Loaders\ContentFactory;
use Naterweb\Content\Renderers\PhpRenderer;
/*
 *
 */

/**
 * Description of class_engineErrorHandler
 *
 * @author nate
 */
class EngineErrorHandler {
    
    	public static function handle_exception( $e ){

    		if ( $e->getCode() >= 100 && $e->getCode() <= 505 ){
    			http_response_code( $e->getCode() ) ;
    			self::handle_http_exception( $e );
    		} else { 
    			header( http_response_code(500) );
    			self::handle_generic_exception( $e );
    		}


		}

	public static function handle_http_exception( $e ){

		if ( DEBUG ){
			self::handle_generic_exception( $e );
		} else {
			$renderer = new PhpRenderer(Naterweb\Engine\Configuration::get_option('template'));
			$renderer->set_value('title', 'Holy ' . $e->getCode() . ' (error), Batman!');
			$renderer->set_value('text', 'Whoops, looks like a problem!');
			$renderer->set_value('content', ContentFactory::loadContentFile(NWEB_ROOT.'/lib/html/errorpage.php'));
			$renderer->render();

		}


	}

	public static function handle_generic_exception( $e ){

		if ( DEBUG ){

			$renderer = new PhpRenderer(Naterweb\Engine\Configuration::get_option('template'));
			$renderer->set_value('title', "Server Error (" . $e->getCode() . ')');


			$stack[] = $e->getFile() . ' line ' . $e->getLine() . ': <strong>' . $e->getMessage() . '</strong>' . 
			"<pre style='border:0px; background:none;'>". $e->getTraceAsString() . '</pre>';

			while ( ($e = $e->getPrevious()) != null ) {

				$stack[] = "<h4>Additionally, the following exception was caught:</h4>";
				$stack[] = $e->getFile() . ' line ' . $e->getLine() . ': <strong>' . $e->getMessage() . '</strong>' . 
				"<pre style='border:0px; background:none;'>". $e->getTraceAsString() . '</pre>';


			}

			$stack[] = "<h5>This page is shown because debug is enabled. Debug can be disabled in the settings.php file.</h5>";



			$renderer->set_value('stack', $stack);
			$renderer->set_value('hasStack', true);
			$renderer->set_value('static', "");
			$renderer->set_value('id', "Error");




		} else {
			$renderer = new PhpRenderer(Naterweb\Engine\Configuration::get_option('template'));

			$renderer->set_value('hasStack', false);
			$renderer->set_value('static', "");
			$renderer->set_value('id', "Error");
			$renderer->set_value('title', "Internal Server Error");
			$renderer->set_value('text', 'Sorry, but the site software was not able to provide the page you requested due to a problem.');


		}

		$renderer->set_value('content', ContentFactory::loadContentFile(NWEB_ROOT.'/lib/html/errorpage.php'));
		$renderer->render();

		die();


	}

}
