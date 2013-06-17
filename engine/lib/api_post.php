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
include GNAT_ROOT.'/engine/lib/core_blog.php';
include GNAT_ROOT.'/engine/lib/core_json.php';


$session = new session( array( "node", "element" ) );
$postData = new article( $session->node );

$element = $session->element;
$filledData = array();
$filledData[ $element ] = $postData->$element;

$jsonData = new jsonMaker( $filledData );

print $jsonData->output();

?>
