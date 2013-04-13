<?php
/*
* Author: Nate Levesque <public@thenaterhood.com>
* Language: PHP
* Filename: api_saneString.php
* 
* Description:
* 	Contains functions for testing and externally accessing the sanitation
*	class so its functioning can be verified and used externally.
*/

include 'core_web.php';
include 'core_json.php';


$requestedVars = array();

foreach( $_GET as $key => $value ){
	$sanitized = new sanitation( $value, 10 );
	$requestedVars[$key] = $sanitized->str;
	
}

$jsonData = new jsonMaker( $requestedVars );

print $jsonData->output();

?>
