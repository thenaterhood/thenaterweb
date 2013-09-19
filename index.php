<?php
#error_reporting(E_ALL);
#ini_set( 'display_errors','1'); 
include GNAT_ROOT.'/lib/core_web.php';

$urlHandler = new urlHandler();
$sectionId = $urlHandler->getControllerId();

include $urlHandler->getController();

$blogdef = new controller();

include $blogdef->template;

?>
