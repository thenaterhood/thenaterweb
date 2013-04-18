<?php
/*
* Author: Nate Levesque <public@thenaterhood.com>
* Language: PHP
* Filename: api_session.php
* 
* Description:
* 	Contains functions for testing and externally accessing the session
* 	class for retrieving session data.
*/

include 'core_web.php';
include 'core_json.php';


$requestedVars = array();

foreach( $_GET as $key => $value ){
	$requestedVars[] = $key;
	
}

print $session->json();
