<?php
include '../core_web.php';
include '../class_webAdmAuth.php';
include '../class_redirect.php';

$session = new session( array( 'user', 'pass', 'id', 'active' ) );

$auth = new webAdmAuth( $session->user, $session->pass, $session->active );

if ( $auth->active ){
	print "You are logged in.";
}
else{
	$redirect = new redirect( $session->uri, 'index.php' );
	$redirect->apply( 302 );
}

?>