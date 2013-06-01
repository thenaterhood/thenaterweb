<h1>Edit An Article</h1>

<form name="create" action="index.php?id=savepost" method="post">
Title: <input type="text" name="title" /><?php print $_POST['title']; ?><br />
Tags: <input type="text" name="tags" /><?php print $_POST['tags']; ?><br />
Blog: <input type="text" name="blog" /><?php print $_POST['blogid']; ?><br />
Write your post: <br /><textarea name="content" rows="100" cols="100" ><?php print $_POST['content']; ?></textarea><br />
<input type="submit" value="Create" />

</form>