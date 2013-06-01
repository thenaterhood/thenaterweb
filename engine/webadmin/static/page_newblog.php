<h1>Create New Blog</h1>
<?php

if ( $_POST['blogid'] ){

	$localpath = '../../'.$_POST['blogid'];

	$writetest = fopen( $localpath.'/writetest.txt', 'w');
	fclose($writetest);
	
	if ( is_writable( $localpath.'/writetest.txt') ){

		$config = file_get_contents('templates/newblog/class_blogdef.php');
		$lines = count( explode( "\n", $config) )+4;

		mkdir( $localpath );
		mkdir( $localpath.'/entries' );
		mkdir( $localpath.'/static' );

		copy( 'templates/newblog/static/page_home.html', $localpath.'/static/page_home.html' );
		copy( 'templates/newblog/static/page_titles.html', $localpath.'/static/page_titles.html' );
		copy( 'templates/newblog/static/page_tags.html', $localpath.'/static/page_tags.html' );
		copy( 'templates/newblog/static/page_post.html', $localpath.'/static/page_post.html' );
		copy( 'templates/newblog/static/template_blognav.php', $localpath.'/static/template_blognav.php' );

		copy( 'templates/newblog/feed.php', $localpath.'/feed.php' );
		copy( 'templates/newblog/index.php', $localpath.'/index.php' );

		if ( ! is_writable('../../'.$_POST['blogid'].'/class_blogdef.php') )
			print '<p>Warning: Gnat cannot write to the configuration file selected, settings cannot be saved.</p>';

		print '<form action="index.php?id=saveconf" method="post">
			<input type="hidden" name="rcfile" value="../../'.$_POST['blogid'].'/class_blogdef.php"/>
			<br />
			<textarea name="content" rows="'.$lines.'" cols="100" >'.$config.'</textarea>
			<br />
			<input type="submit" value="Save and Apply" />
			</form>';
	}
	else{
		print '<p>Gnat does not have write access to the required locations to create a new blog.</p>';
	}

}
else{

	print '<form name="create" action="index.php?id=newblog" method="post">
	Please enter a blog name to edit: <input type="text" name="blogid" />
	<input type="submit" value="Continue" />
	</form>';

}

?>