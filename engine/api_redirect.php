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
include_once 'class_redirect.php';

// This allows for a place to be set, although it's messy.
// This api is basically just for testing the inventory functionality
$session = new session( array( "from", "to" ) );
$redirect = new redirect( $session->from, $session->to );

$redirect->view();

?>
