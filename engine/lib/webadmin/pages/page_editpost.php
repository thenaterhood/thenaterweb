
<h1>Edit a Post</h1>

<?php

/**
 *
 * Retrieves a post json file into an array
 * or returns an empty array if it does not exist
 * @param $postFile - the file to retrieve
 * @return $postData - the retrieved data
 */
function retrievePost( $postFile ){

	if ( file_exists($postFile) ){

		$postData = json_decode( file_get_contents($postFile), True );

		if ( is_array( $postData['tags'] ) )
			$postData['tags'] = implode(', ', $postData['tags'] );

		if ( is_array( $postData['content'] ) )
			$postData['content'] = implode( $postData['content'] );

	}

	else{
		$postData = array();
	}

	return $postData;

}

if ( $admSession->blogid ){

	if ( isset( $_GET['postid'] ) || isset( $_GET['isnew'] ) ){

		print '<h2>'.$admSession->blogid.'</h2>';

		$blogdef = loadBlogConf( strtolower($admSession->blogid) );

		$postData = retrievePost( $blogdef->post_directory.'/'.$_GET['postid'] );

		print'<form name="create" action="'.getConfigOption('site_domain').'/webadmin/savepost" method="post">
		Title: <br />
		<textarea name="title" rows="1" cols="100">'.$postData['title'].'</textarea><br />
		Tags - comma separated: <br />
		<textarea name="tags" rows="1" cols="100">'.$postData['tags'].'</textarea><br />
		Blog: <br />
		<textarea name="blog" rows="1" cols="100">'.$admSession->blogid.'</textarea><br />
		Write your post - <strong>full html required</strong>: <br />
		<textarea name="content" rows="50" cols="100" >'.$postData['content'].'</textarea><br />
		<input type="hidden" name="file" value="'.$_GET['postid'].'" />
		<input type="hidden" name="postpath" value=\''.$blogdef->post_directory.'\' />
		<input type="submit" value="Create" />

		</form>';


	}

	else{

		$blogdef = loadBlogConf( strtolower($admSession->blogid) );
		$handler = opendir( $blogdef->post_directory );
		$avoid = getConfigOption('hidden_files');

		while( $file = readdir( $handler ) ){
			if ( $file != '..' && $file != '.' )
				print '<p><a href="'.getConfigOption('site_domain').'/webadmin/editpost/blogid/'.$admSession->blogid.'/postid/'.$file.'">'.$file.'</a></p>';
		}

	}

}
else{

		$found = array();
		$handler = opendir(GNAT_ROOT.'/config/section.d');
		print '<ul>';

		while ($file = readdir($handler)){

			if ( $file != '.' && $file != '..' && !in_array($file, $found) ){
				$blogid=substr($file, 0, strpos($file, ".") );
				$found[] = $file;
				print '<li><a href="'.getConfigOption('site_domain').'/webadmin/editpost/blogid/'.$blogid.'">'.$blogid.'</a></li>'."\n";
			}
		}

}

?>
<br />
<br />
<p><a href="<?php print getConfigOption('site_domain'); ?>/webadmin">Back to webadmin panel (discarding changes)</a></p>