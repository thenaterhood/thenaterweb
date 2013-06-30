<?php
/**
* Contains functions for testing and externally accessing the session
* class for retrieving session data.
* @author Nate Levesque <public@thenaterhood.com>
*/

/**
 * Includes the necessary facilities
 */
include GNAT_ROOT.'/lib/core_web.php';
include GNAT_ROOT.'/lib/core_json.php';


$requestedVars = array();

foreach( $_GET as $key => $value ){
	$requestedVars[] = $key;
	
}
$session = new session( $requestedVars );

print $session->json();
