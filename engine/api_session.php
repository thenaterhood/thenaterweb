<?php
/*
* Author: Nate Levesque <public@thenaterhood.com>
* Language: PHP
* Filename: api_sanitize.php
* 
* Description:
* 	Contains functions for testing and externally accessing the session
* 	class for sanitizing input
*/

include 'core_web.php';
include 'core_json.php';


$requestedVars = array();

foreach( $_GET as $key => $value ){
	$requestedVars[] = $key;
	
}

$session = new session( $requestedVars );
$jsonData = new jsonMaker( $session->dump() );

print $jsonData->output();
