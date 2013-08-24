<?php
/**
* 
* Contains functions for testing the functionality of the inventory class
* @author Nate Levesque <public@thenaterhood.com>
*/

/**
 * Includes the necessary facilities
 */
include_once GNAT_ROOT.'/lib/core_web.php';
include_once GNAT_ROOT.'/lib/core_json.php';
include_once GNAT_ROOT.'/classes/class_inventory.php';

// This allows for a place to be set, although it's messy.
// This api is basically just for testing the inventory functionality
$session = new session( array( "field", "value", "inventory" ) );

$inventory = new inventory( $session->inventory, "inventory" );

$matching = $inventory->select( $session->field, $session->value );
$article = $matching[0];


$jsonData = new jsonMaker( $article);

print $jsonData->output();

?>
