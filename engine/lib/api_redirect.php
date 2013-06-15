<?php
/** 
* Provides an interface for unit testing the redirect class.
* Does not actually redirect, just returns what the class was
* initialized with in order to verify it can be initialized correctly.
*
* @author Nate Levesque <public@thenaterhood.com>
*/

/**
 * Includes the necessary facilities
 */
include_once 'core_web.php';
include_once '../classes/class_redirect.php';

$session = new session( array( "from", "to" ) );
$redirect = new redirect( $session->from, $session->to );

$redirect->view();

?>
