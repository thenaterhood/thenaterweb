<?php
/**
 * Creates a new blog on the site by copying over
 * the template and redirecting to set up the config
 * @author Nate Levesque <public@thenaterhood.com>
 */
if ( isset( $_POST['blogid'] ) ){

	include '../../class_redirect.php';

	$localpath = '../../'.$_POST['blogid'];

	mkdir( $localpath );

	$writetest = fopen( $localpath.'/writetest.txt', 'w');
	fclose($writetest);

	if ( is_writable( $localpath.'/writetest.txt') ){

		unlink( $localpath.'/writetest.txt' );
		$config = file_get_contents('templates/newblog/class_blogdef.php');
		$lines = count( explode( "\n", $config) )+4;

		mkdir( $localpath.'/entries' );
		mkdir( $localpath.'/static' );

		copy( 'templates/newblog/static/page_home.html', $localpath.'/static/page_home.html' );
		copy( 'templates/newblog/static/page_titles.html', $localpath.'/static/page_titles.html' );
		copy( 'templates/newblog/static/page_tags.html', $localpath.'/static/page_tags.html' );
		copy( 'templates/newblog/static/page_post.html', $localpath.'/static/page_post.html' );
		copy( 'templates/newblog/static/template_blognav.php', $localpath.'/static/template_blognav.php' );
		copy( 'templates/newblog/class_blogdef.php', $localpath.'/class_blogdef.php' );

		copy( 'templates/newblog/feed.php', $localpath.'/feed.php' );
		copy( 'templates/newblog/index.php', $localpath.'/index.php' );

		$redirect = new redirect( "newblog", "index.php?id=editblog&blogid=".$_POST['blogid'] );
		$redirect->apply( 302 );

	}
	else{
		print '<p>Gnat does not have write access to the required locations to create a new blog.</p>';
	}

}
else{

	print '<h1>Create New Blog</h1>';

	print '<form name="create" action="index.php?id=newblog" method="post">
	Please enter a blog name to edit: <input type="text" name="blogid" />
	<input type="submit" value="Continue" />
	</form>';

}

?>