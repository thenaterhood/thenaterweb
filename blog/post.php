<?php 
include GNAT_ROOT.'/lib/core_blog.php';
include GNAT_ROOT.'/lib/core_redirect.php';


$session = new session( array('name', 'track', 'konami', 'node') );
$config = new config();

$redirect = new redirect( "post.php", "/blog/read/".$session->node.".htm", $session->uri );
$redirect->apply( 301 );

?>