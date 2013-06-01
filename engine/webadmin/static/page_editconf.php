<h1>Edit Engine Configuration</h1>
<form name="create" action="index.php?id=saveconf" method="post">
Title: <input type="text" name="title" /><br />
Tags: <input type="text" name="tags" /><br />
Blog: <input type="text" name="blog" /><br />
Write your post: <br /><textarea id="content" rows="100" cols="100" ><?php print file_get_contents('../class_config.php'); ?></textarea><br />
<input type="submit" value="Save" />
</form>
<?php

?>