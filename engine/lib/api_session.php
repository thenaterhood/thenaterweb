<?php
/**
* Contains functions for testing and externally accessing the session
* class for retrieving session data.
* @author Nate Levesque <public@thenaterhood.com>
*/

/**
 * Includes the necessary facilities
 */
include $_SERVER['DOCUMENT_ROOT'].'/engine/lib/core_web.php';
include $_SERVER['DOCUMENT_ROOT'].'/engine/lib/core_json.php';


$requestedVars = array();

foreach( $_GET as $key => $value ){
	$requestedVars[] = $key;
	
}
$session = new session( $requestedVars );

print $session->json();
