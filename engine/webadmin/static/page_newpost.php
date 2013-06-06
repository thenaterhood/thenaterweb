<h1>Create An Article</h1>

<?php


	print '<form name="create" action="index.php?id=editpost" method="post">
	Please enter a blog name to edit: <input type="text" name="blogid" />
	<input type="hidden" name="isnew" value="True" />
	<input type="submit" value="Continue" />
	</form>';


?>