<?php
/*
* Author: Nate Levesque <public@thenaterhood.com>
* Language: PHP
* Filename: api_post.php
* 
* Description:
* 	Contains functions for testing and externally accessing data from
*	blog posts.
*/

include 'core_blog.php';
include 'core_json.php';


$session = new session( array( "node", "element" ) );
$postData = new postObj( $session->node );

$element = $session->element;
$filledData = array();
$filledData[ $element ] = $postData->$element;

$jsonData = new jsonMaker( $filledData );

print $jsonData->output();

?>
