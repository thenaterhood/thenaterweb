<?php

/**
 * Define the root of the naterweb 
 * engine directory. Many things internally 
 * rely on paths relative to this, so it is 
 * recommended you install the engine to the 
 * web root. If you install the engine elsewhere, 
 * you will need to adjust this path.
 * 
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

include_once NWEB_ROOT.'/classes/class_applications.php';
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

use Naterweb\Engine\Applications;
use Naterweb\Engine\Configuration;
use Naterweb\Routing\Urls\UrlHandler;

/**
 * This array contains the applications enabled 
 * on thenaterweb. It is an associative array, 
 * containing the name of the app (url to route 
 * to it, ie /page/about would load the application 
 * with the url designated as "page". Anything not 
 * added to this array will route to a 404 error page.
 */
$_INSTALLED_APPS = array( 
	'sitemaps'=>    NWEB_ROOT.'/lib/builtins/sitemaps',
	'auth'=>        NWEB_ROOT.'/lib/builtins/auth',
        'page'=>        NWEB_ROOT.'/lib/builtins/simplepage',
        'webadmin'=>    'apps/webadmin',
        'blog'=>        'apps/blog'
	);

Applications::setup_installed($_INSTALLED_APPS);

/**
 * This array contains aliases for applications. 
 * With the current design, applications must be 
 * defined in a class with the same name as the 
 * name described in the $_INSTALLED_APPS array. 
 * They can be aliased with additional names in this 
 * array, which should be of the format "alias"=>"realname"
 * This can be used to add additional canonical names for 
 * an app to handle common typos or things like that. The 
 * builtin apps are aliased so that both the plural and 
 * non-plural forms of their names will work, as 
 * an example. Some applications may allow for different 
 * functionality based on the alias used, as the alias 
 * requested (REQUESTED_NAME) is stored and accessible 
 * to applications.
 */
$_APP_ALIASES = array(
    
        'pages'=>       'page',
        'sitemap'=>     'sitemaps',
        'devrants' => 	'blog'
    
    
        );

Applications::setup_aliases( $_APP_ALIASES );
$sessionmgr = SessionMgr::getInstance();

/**
 * Manage redirects to "friendly" URLs if the configuration
 * option is set.
 */
if ( Configuration::get_option('friendly_urls') && ! $sessionmgr->noRedirect ){
    $redirect = new Naterweb\Routing\Redirects\ConditionalRedirect( '/?url', '/'.$_GET['url'], substr( Configuration::get_option('site_domain').request::meta('REQUEST_URI'), 7 ) );
    $redirect->apply( 301 );
}

$sessionmgr->noRedirect = False;

# Initialize the URL handler and use it to include 
# the relevant controller from controllers.
$urlHandler = new UrlHandler();
$controller = request::sanitized_get('controller');

define( 'REQUESTED_NAME', $controller );

# Handle application aliases
if (array_key_exists($controller, $_APP_ALIASES)){
    $controller = $_APP_ALIASES[$controller];
}

# Handle the actual loading of the application and 
# calling the requested view.
if (array_key_exists($controller, $_INSTALLED_APPS) ){

	define(strtoupper($controller).'_ROOT', $_INSTALLED_APPS[$controller] );
	define("APP_NAME", $controller);
	$approot = $_INSTALLED_APPS[$controller];


} else {

	$controller = 'error';
	define('ERROR_ROOT', "apps/".$controller );
	define("APP_NAME", $controller);

	$approot = 'apps/'.$controller;

}

$id = preg_replace("/[^a-zA-Z0-9\s]/", "", request::variable('id'));

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


	EngineErrorHandler::handle_exception( $e );


}


?>
