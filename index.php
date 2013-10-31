<?php
#error_reporting(E_ALL);
#ini_set( 'display_errors','1'); 
define("GNAT_ROOT", "engine");
$ENGINE_BUILTINS = array( 'feeds', 'sitemaps' );


# These include the core utilities that Thenaterweb requires.
# core_blog imports all of the various utilities in one shot.
include_once GNAT_ROOT.'/lib/core_blog.php';

# This includes the base controller that all controllers must 
# extend in order to properly work.
include_once GNAT_ROOT.'/lib/interface_controller.php';

# Initialize the URL handler and use it to include 
# the relevant controller from controllers.
$useBuiltin = false;
$urlHandler = new urlHandler();
$sectionId = $urlHandler->getControllerId();

# Manage builtin features such as feeds and sitemaps 
# rather than using the selected controller to perform 
# these tasks.
if ( in_array($sectionId, $ENGINE_BUILTINS) ){

	$feature = $sectionId;
	$useBuiltin = true;
	$urlHandler->reparseUrl();
	$sectionId = $urlHandler->getControllerId();


}


define("CONTROLLER_ROOT", "controller/".$sectionId );

include $urlHandler->getController();


# Initialize the controller
$blogdef = new controller();

# Display what the controller has defined as an output
# file. This may be a PHP script or a flat file. If the 
# request points to a builtin feature, generate content 
# from the feature instead and don't include a controller 
# view.
if ( $useBuiltin ){

	include GNAT_ROOT.'/lib/builtins/'.$feature.'.php';


} else{

	include $blogdef->template;

}

?>
