<?php

include '../core_blog.php';

print '<form name="create" action="savepost.php" method="post">
Title: <input type="text" name="title" /><br />
Tags: <input type="text" name="tags" /><br />
Blog: <input type="text" name="blog" /><br />
Write your post: <br /><textarea id="content" rows="200" cols="200" /><br />
<input type="submit" value="Save" />
</form>';


?>