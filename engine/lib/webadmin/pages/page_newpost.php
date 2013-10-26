<h1>Create An Article</h1>
<?php 
	$found = array();
	$handler = opendir(GNAT_ROOT.'/config/section.d');
	print '<ul>';

	while ($file = readdir($handler)){

		if ( strpos( $file, '.conf.xml' ) && !in_array($file, $found) ){
			$blogid=substr($file, 0, strpos($file, ".") );
			$found[] = $file;
			print '<li><a href="'.getConfigOption('site_domain').'/webadmin/editpost/isnew/True/blogid/'.$blogid.'">'.$blogid.'</a></li>'."\n";
		}
	}

?>