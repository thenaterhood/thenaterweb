<h1>Create An Article</h1>
<?php 
	$found = array();
	$handler = opendir(GNAT_ROOT.'/config/section.d');
	print '<ul>';

	$controllers = getControllers();

	foreach ($controllers as $blogid) {

			print '<li><a href="'.getConfigOption('site_domain').'/webadmin/editpost/isnew/True/blogid/'.$blogid.'">'.$blogid.'</a></li>'."\n";
	}

?>