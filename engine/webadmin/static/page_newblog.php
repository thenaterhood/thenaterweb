<?php
/**
 * Creates a new blog on the site by copying over
 * the template and redirecting to set up the config
 * @author Nate Levesque <public@thenaterhood.com>
 */
if ( isset( $_POST['blogid'] ) ){

	include GNAT_ROOT.'/classes/class_redirect.php';

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


		copy( 'templates/newblog/static/template_blognav.php', $localpath.'/static/template_blognav.php' );
		copy( 'templates/newblog/class_blogdef.php', $localpath.'/class_blogdef.php' );

		$feedFile = file_get_contents('templates/newblog/feed.php');
		$contents = str_replace('<?php', '</php'."\n".'$id='.$session->blogid.";\n", $feedFile);

		$file = fopen( $localpath.'/feed.php' );
		fwrite( $file, $contents );
		fclose( $file );

		$feedFile = file_get_contents('templates/newblog/index.php');
		$contents = str_replace('<?php', '</php'."\n".'$id='.$session->blogid.";\n", $feedFile);

		$file = fopen( $localpath.'/index.php' );
		fwrite( $file, $contents );
		fclose( $file );


		copy( 'templates/newblog/conf.xml', GNAT_ROOT.'/config/section.d/'.$session->blogid.'.conf.xml' );

		print '<p><a href="index.php?id=editblog&blogid='.$_POST['blogid'].'">Click to continue to configure new blog (required)</a></p>';

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