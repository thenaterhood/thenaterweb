<?php

include '../core_blog.php';
include_once '../class_redirect.php';
include '../class_webAdmAuth.php';

$session = new session( array( 'user', 'pass', 'active' ) );
$auth = new webAdmAuth( $session->user, $session->pass, $session->active );

if ( $auth->isAuthenticated() ){

	print '<form name="create" action="savepost.php" method="post">
    Title: <input type="text" name="title" /><br />
    Tags: <input type="text" name="tags" /><br />
    Blog: <input type="text" name="blog" /><br />
    Write your post: <br /><textarea id="content" rows="1000" cols="1000"><br />
    <input type="submit" value="Save" />
	</form>';
}
else{

	$redirect = new redirect( $session->uri, 'index.php' );
	

}

?>