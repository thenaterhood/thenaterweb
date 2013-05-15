<?php
/*
* Author: Nate Levesque <public@thenaterhood.com>
* Language: PHP
* Filename: api_inventory.php
* 
* Description:
* 	Contains functions for testing the functionality of the inventory class
*/

include_once 'core_web.php';
include_once 'core_json.php';
include_once 'class_inventory.php';

// This allows for a place to be set, although it's messy.
// This api is basically just for testing the inventory functionality
$session = new session( array( "place", "element" ) );

$inventory = new inventory( getConfigOption('post_directory') );

$matching = $inventory->select( 'title', "Lovin' those Facebook Likes" );

$filledData['title'] = $matching[0]->title;
$filledData['tags'] = $matching[0]->tags;


$jsonData = new jsonMaker( $filledData );

print $jsonData->output();

?>
