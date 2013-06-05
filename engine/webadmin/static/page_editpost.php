
<h1>Edit a Post</h1>

<?php

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

if ( $_POST['blogid'] || $_GET['blogid'] ){

	if ( $_GET['postid'] || $_POST['isnew'] ){

		$postData = retrievePost( '../../'.$_GET['blogid'].'/entries/'.$_GET['postid'] );

		print'<form name="create" action="index.php?id=savepost" method="post">
		Title: <input type="text" name="title" value="'.$postData['title'].'" /><br />
		Tags: <input type="text" name="tags" value="'.$postData['tags'].'" /><br />
		Blog: <input type="text" name="blog" value="'.$_GET['blogid'].'" /><br />
		Write your post: <br /><textarea name="content" rows="100" cols="100" >'.$postData['content'].'</textarea><br />
		<input type="hidden" name="file" value="'.$_GET['postid'].'" />
		<input type="submit" value="Create" />

		</form>';


	}

	else{

		$handler = opendir('../../'.$_POST['blogid'].'/entries');
		$avoid = getConfigOption('hidden_files');

		while( $file = readdir( $handler ) ){
			if ( $file != '..' && $file != '.' )
				print '<p><a href="?id=editpost&blogid='.$_POST['blogid'].'&postid='.$file.'">'.$file.'</a></p>';
		}

	}

}
else{

	print '<form name="create" action="index.php?id=editpost" method="post">
	Please enter a blog name to edit a post on: <input type="text" name="blogid" />
	<input type="submit" value="Continue" />
	</form>';

}

?>
