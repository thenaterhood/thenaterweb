<?php

/**
 * Define the root of the naterweb 
 * engine directory. Many things internally 
 * rely on paths relative to this, so it is 
 * recommended you install the engine to the 
 * web root.
 */
define("NWEB_ROOT", "engine");

/**
 * Enables or disables debug mode.
 */
define("DEBUG", True);

if ( DEBUG ){
	error_reporting(E_ALL);
	ini_set( 'display_errors','1'); 

}


include_once NWEB_ROOT.'/classes/class_engine.php';

/**
 * These include the core utilities that Thenaterweb requires.
 * core_blog imports all of the various utilities in one shot.
 */
include_once NWEB_ROOT.'/lib/core_blog.php';

/**
 * This includes the base controller that all controllers must 
 * extend in order to properly work.
 */
include_once NWEB_ROOT.'/lib/interface_controller.php';

/**
 * Include the redirect mechanism
 */
include_once NWEB_ROOT.'/lib/core_redirect.php';

/**
 * Set up the main variables for Thenaterweb's 
 * operation
 */
$_ENGINE_BUILTINS = array( 
	'feeds', 
	'sitemaps',
	'auth',
        'robots.txt'
	);

$CONFIG = new config();
$NWSESSION = request::get_sanitized_as_object( array() );



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
$urlHandler = new urlHandler();
$controller = $urlHandler->getControllerId();

# Manage builtin features such as feeds and sitemaps 
# rather than using the selected controller to perform 
# these tasks.
if ( in_array($controller, $_ENGINE_BUILTINS) ){

	define(strtoupper($controller).'_ROOT', NWEB_ROOT."/lib/builtins/".$controller );
	define("APP_NAME", $controller);
	$approot = NWEB_ROOT."/lib/builtins/".$controller;


} else if ( file_exists('apps/'.$controller.'/main.php') ) { 

	define(strtoupper($controller).'_ROOT', "apps/".$controller );
	define("APP_NAME", $controller);

	$approot = 'apps/'.$controller;

} else {

	$controller = 'error';
	define('ERROR_ROOT', "apps/".$controller );
	define("APP_NAME", $controller);

	$approot = 'apps/'.$controller;

}

$NWSESSION = request::get_sanitized_as_object( array( 'id' ) );

$id = $NWSESSION->id;

include $approot.'/main.php';


# Initialize the controller
$blogdef = new $controller();

# Display what the controller has defined as an output
# file. This may be a PHP script or a flat file. If the 
# request points to a builtin feature, generate content 
# from the feature instead and don't include a controller 
# view.


try { 

	if ( method_exists( $blogdef, $id ) || method_exists( $blogdef, '__call' ) ){

		$blogdef->$id();
		die();

	} else {

		$page = (object)$blogdef->getPageData();
		include $blogdef->template;
		die();

	}

} catch ( Exception $e ){


	engine::handle_exception( $e );


}


?>
