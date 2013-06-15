<?php
/**
* Contains functions for testing and externally accessing the sanitation
* class so its functioning can be verified and used externally.
* @author Nate Levesque <public@thenaterhood.com>
*
*/

/**
 * Includes the necessary facilities
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
