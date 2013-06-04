
<h1>Edit a Post</h1>

<?php

if ( $_POST['blogid'] || $_GET['blogid'] ){

	if ( $_POST['isnew'] ){

		print '<form name="create" action="index.php?id=savepost" method="post">
		Title: <input type="text" name="title" /><br />
		Tags: <input type="text" name="tags" /><br />
		Blog: <input type="text" name="blog" value="'.$_POST['blogid'].'"/><br />
		Write your post: <br /><textarea name="content" rows="100" cols="100" ></textarea><br />
		<input type="hidden" name="file" value="" />
		<input type="hidden" name="isnew" value="True" />
		<input type="submit" value="Create" />

		</form>';



	}

	else if ( $_GET['postid'] ){


	}

	else{

		include '../class_inventory.php';




	}

}
else{

	print '<form name="create" action="index.php?id=editpost" method="post">
	Please enter a blog name to edit a post on: <input type="text" name="blogid" />
	<input type="submit" value="Continue" />
	</form>';

}

?>

<form name="create" action="index.php?id=savepost" method="post">
Title: <input type="text" name="title" value="<?php print $_POST['title']; ?>" /><br />
Tags: <input type="text" name="tags" value="<?php print $_POST['tags']; ?>" /><br />
Blog: <input type="text" name="blog" value="<?php print $_POST['blogid']; ?>" /><br />
Write your post: <br /><textarea name="content" rows="100" cols="100" ><?php print $_POST['content']; ?></textarea><br />
<input type="hidden" name="file" value="new" />
<input type="submit" value="Create" />

</form>
