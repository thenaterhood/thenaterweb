
<h1>Edit a Post</h1>

<?php

if ( $_POST['blogid'] || $_GET['blogid'] ){

	if ( $_POST['isnew'] ){

		print '<form name="create" action="index.php?id=savepost" method="post">
		Title: <input type="text" name="title" /><br />
		Tags: <input type="text" name="tags" /><br />
		Visible Date: <input type="text" name="date" /><br />
		Blog: <input type="text" name="blog" value="'.$_POST['blogid'].'"/><br />
		Write your post: <br /><textarea name="content" rows="100" cols="100" ></textarea><br />
		<input type="hidden" name="file" value="" />
		<input type="hidden" name="isnew" value="True" />
		<input type="submit" value="Create" />

		</form>';



	}

	else if ( $_GET['postid'] ){

		$jsonData = file_get_contents( '../../'.$_GET['blogid'].'/entries/'.$_GET['postid'] );
		$decodedData = json_decode($jsonData, True);

		if ( is_array( $decodedData['tags'] ) )
			$decodedData['tags'] = implode(', ', $decodedData['tags'] );

		if ( is_array( $decodedData['content'] ) )
			$decodedData['content'] = implode( $decodedData['content'] );

		print'<form name="create" action="index.php?id=savepost" method="post">
		Title: <input type="text" name="title" value="'.$decodedData['title'].'" /><br />
		Tags: <input type="text" name="tags" value="'.$decodedData['tags'].'" /><br />
		Blog: <input type="text" name="blog" value="'.$_GET['blogid'].'" /><br />
		Write your post: <br /><textarea name="content" rows="100" cols="100" >'.$decodedData['content'].'</textarea><br />
		<input type="hidden" name="file" value="'.$_GET['postid'].'" />
		<input type="submit" value="Create" />

		</form>';


	}

	else{

		$handler = opendir('../../'.$_POST['blogid'].'/entries');

		while( $file = readdir( $handler ) ){
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
