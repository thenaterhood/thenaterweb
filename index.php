<?php
#error_reporting(E_ALL);
#ini_set( 'display_errors','1'); 
include_once GNAT_ROOT.'/classes/class_urlHandler.php';

$urlHandler = new urlHandler();
$sectionId = $urlHandler->getControllerId();

include $urlHandler->getController();

$blogdef = new controller();

include $blogdef->template;

?>
