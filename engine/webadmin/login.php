<?php
include '../core_web.php';
include '../class_webAdmAuth.php';
include '../class_redirect.php';

$session = new session( array( 'user', 'pass', 'id', 'active' ) );

$auth = new webAdmAuth( $session->user, $session->pass, $session->active );

if ( $auth->isAuthenticated() ){
	print "Welcome $session->user. Administering to the site at ".getConfigOption('site_domain');
	print '<ul>
	<li><a href="newpost.php">Create a new Post or Article</a></li>
	<li><a href="editpost.php">Edit an existing post or article</a></li>
	<li><a href="newblog.php">Create a new blog or article collection</a></li>
	</ul>';
}
else{
	$redirect = new redirect( $session->uri, 'index.php' );
	$redirect->apply( 302 );
}

?>