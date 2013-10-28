<?php
#error_reporting(E_ALL);
#ini_set( 'display_errors','1'); 
define("GNAT_ROOT", "engine");

include_once GNAT_ROOT.'/lib/core_blog.php';
include_once GNAT_ROOT.'/lib/interface_controller.php';

$urlHandler = new urlHandler();
$sectionId = $urlHandler->getControllerId();

include $urlHandler->getController();

$blogdef = new controller();

include $blogdef->template;

?>
