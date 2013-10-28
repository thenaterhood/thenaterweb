<?php
#error_reporting(E_ALL);
#ini_set( 'display_errors','1'); 
define("GNAT_ROOT", "engine");


# These include the core utilities that Thenaterweb requires.
# core_blog imports all of the various utilities in one shot.
include_once GNAT_ROOT.'/lib/core_blog.php';

# This includes the base controller that all controllers must 
# extend in order to properly work.
include_once GNAT_ROOT.'/lib/interface_controller.php';

# Initialize the URL handler and use it to include 
# the relevant controller from controllers.
$urlHandler = new urlHandler();
$sectionId = $urlHandler->getControllerId();

include $urlHandler->getController();


# Initialize the controller
$blogdef = new controller();

# Display what the controller has defined as an output
# file. This may be a PHP script or a flat file.
include $blogdef->template;

?>
