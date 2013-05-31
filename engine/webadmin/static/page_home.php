<?php

	print "Welcome! Administering to the site at ".getConfigOption('site_domain');
	print '<ul>
	<li><a href="newpost.php">Create a new Post or Article</a></li>
	<li><a href="editpost.php">Edit an existing post or article</a></li>
	<li><a href="newblog.php">Create a new blog or article collection</a></li>
	<li><a href="editblog.php">Edit an existing blog or article configuration</a></li>
	<li><a href="editconf.php">Modify engine configuration</a></li>
	</ul>';

?>