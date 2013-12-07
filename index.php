<?php

define("GNAT_ROOT", "engine");
define("DEBUG", False);

if ( DEBUG ){
	error_reporting(E_ALL);
	ini_set( 'display_errors','1'); 

}


/**
 * These include the core utilities that Thenaterweb requires.
 * core_blog imports all of the various utilities in one shot.
 */
include_once GNAT_ROOT.'/lib/core_blog.php';

/**
 * This includes the base controller that all controllers must 
 * extend in order to properly work.
 */
include_once GNAT_ROOT.'/lib/interface_controller.php';

/**
 * Include the redirect mechanism
 */
include_once GNAT_ROOT.'/lib/core_redirect.php';

/**
 * Set up the main variables for Thenaterweb's 
 * operation
 */
$_ENGINE_BUILTINS = array( 'feeds', 'sitemaps' );
$CONFIG = new config();


/**
 * Manage redirects to "friendly" URLs if the configuration
 * option is set.
 */
if ( $CONFIG->friendly_urls ){
    $redirect = new condRedirect( '/?url', '/'.$_GET['url'], substr( $CONFIG->site_domain, 7 ).$NWSESSION->uri );
    $redirect->apply( 301 );
}

# Initialize the URL handler and use it to include 
# the relevant controller from controllers.
$useBuiltin = false;
$urlHandler = new urlHandler();
$controller = $urlHandler->getControllerId();

# Manage builtin features such as feeds and sitemaps 
# rather than using the selected controller to perform 
# these tasks.
if ( in_array($controller, $_ENGINE_BUILTINS) ){

	$feature = $controller;
	$useBuiltin = true;
	$urlHandler->reparseUrl();
	$controller = $urlHandler->getControllerId();


}


define(strtoupper($controller).'_ROOT', "controller/".$controller );

include $urlHandler->getController();
$NWSESSION = new session( array( 'id' ) );

$id = $NWSESSION->id;




# Initialize the controller
$blogdef = new $controller();

# Display what the controller has defined as an output
# file. This may be a PHP script or a flat file. If the 
# request points to a builtin feature, generate content 
# from the feature instead and don't include a controller 
# view.
if ( $useBuiltin ){

	include GNAT_ROOT.'/lib/builtins/'.$feature.'.php';


} else{

	try { 


		if ( function_exists( $blogdef->$id() ) ){

			$blogdef->$id();

		} else {

			$pageData = $blogdef->getPageData();
			include $blogdef->template;

		}

	} catch ( Exception $e ){

		echo "404: The requested page could not be found.";

	}

}

?>
