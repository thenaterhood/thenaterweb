<?php

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
			$pageData = array();
			$pageData['title'] = 'Holy ' . $e->getCode() . ' (error), Batman!';
			$pageData['text'] = "Whoops, looks like a problem!";

			$pageData['content'] = pullContent('/home/nate/gnat/engine/lib/html/errorpage');
			render_php_template( getConfigOption('template'), $pageData );

		}


	}

	public static function handle_generic_exception( $e ){

		if ( DEBUG ){

			$pageData = array();
			$pageData['title'] = "Server Error (" . $e->getCode() . ')';

			$stack = array();
			$stack[] = $e->getFile() . ' line ' . $e->getLine() . ': <strong>' . $e->getMessage() . '</strong>' . 
			"<pre style='border:0px; background:none;'>". $e->getTraceAsString() . '</pre>';

			while ( ($e = $e->getPrevious()) != null ) {

				$stack[] = "<h4>Additionally, the following exception was caught:</h4>";
				$stack[] = $e->getFile() . ' line ' . $e->getLine() . ': <strong>' . $e->getMessage() . '</strong>' . 
				"<pre style='border:0px; background:none;'>". $e->getTraceAsString() . '</pre>';


			}

			$stack[] = "<h5>This page is shown because debug is enabled. Debug can be disabled in the settings.php file.</h5>";



			$pageData['stack'] = $stack;
			$pageData['hasStack'] = true;
			$pageData['static'] = "";
			$pageData['id'] = "Error";




		} else {

			$pageData = array();
			$pageData['hasStack'] = false;
			$pageData['static'] = "";
			$pageData['id'] = "Error";
			$pageData['title'] = "Internal Server Error";
			$pageData['text'] = 'Sorry, but the site software was not able to provide the page you requested due to a problem.';


		}

		$pageData['content'] = pullContent('/home/nate/gnat/engine/lib/html/errorpage');
		render_php_template( getConfigOption('template'), $pageData );

		die();


	}

}
