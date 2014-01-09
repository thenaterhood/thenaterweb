<?php

/**
 * Contains internal naterweb functionality for 
 * handling some generic internal features such as 
 * error handling.
 */
class engine{
    
    private static $configuration;
    private static $CONFIG_LOADED = false;

	public static function handle_exception( $e ){

		if ( DEBUG ){

			$stack = array();
			$stack[] = $e->getFile() . ' line ' . $e->getLine() . ': <strong>' . $e->getMessage() . '</strong>' . 
			"<pre style='border:0px; background:none;'>". $e->getTraceAsString() . '</pre>';

			while ( ($e = $e->getPrevious()) != null ) {

				$stack[] = "<h4>Additionally, the following exception was caught:</h4>";
				$stack[] = $e->getFile() . ' line ' . $e->getLine() . ': <strong>' . $e->getMessage() . '</strong>' . 
				"<pre style='border:0px; background:none;'>". $e->getTraceAsString() . '</pre>';


			}

			$stack[] = "<h5>This page is shown because debug is enabled. Debug can be disabled in the settings.php file.</h5>";



			$pageData = array();
			$pageData['stack'] = $stack;
			$pageData['title'] = "Uncaught Exception (assumed fatal)";

			render_php_template( NWEB_ROOT.'/lib/html/error.html', $pageData );
		} else {

			echo "<h1>Error 500:</h1> <p>The requested page could not be loaded due to a problem encountered by the site software.</p>";

		}

		die();


	}
        
        public static function get_option( $option ){
            
            if ( ! self::$CONFIG_LOADED ){
                
                self::$configuration = new config();
                self::$CONFIG_LOADED = true;
                
            }
            
            return self::$configuration->$option;
                        
            
            
        }

}

?>