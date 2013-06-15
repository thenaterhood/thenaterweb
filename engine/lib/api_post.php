<?php
/**
* 
* Contains functions for testing and externally accessing data from
* blog posts.
* @author Nate Levesque <public@thenaterhood.com>
*/

/**
 * Includes the necessary facilities
 */
include 'core_blog.php';
include 'core_json.php';


$session = new session( array( "node", "element" ) );
$postData = new article( $session->node );

$element = $session->element;
$filledData = array();
$filledData[ $element ] = $postData->$element;

$jsonData = new jsonMaker( $filledData );

print $jsonData->output();

?>
