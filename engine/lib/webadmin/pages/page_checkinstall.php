<h1>Installation Details</h1>

<?php


echo '<h3>Controllers known to Thenaterweb</h3>';

$found = array();
print '<ul>';

$controllers = getControllers();

foreach ($controllers as $blogid) {
	print '<li><a href="'.getConfigOption('site_domain').'/webadmin/editblog/blogid/'.$blogid.'">'.$blogid.'</a></li>'."\n";
}
print '</ul>';
print '<br />';

$writetest = fopen( getConfigOption('dynamic_directory').'/writetest.txt', 'w');
fclose($writetest);

# Check if dynamic directory is writeable
if ( is_writable( getConfigOption('dynamic_directory').'/writetest.txt') ){

	unlink( getConfigOption('dynamic_directory').'/writetest.txt' );
	print '	<div class="alert alert-success">
		<button type="button" class="close" data-dismiss="alert">&times;</button>
		OK: Dynamic storage is writeable.
		</div>';
}else{

	print '	<div class="alert alert-warning">
		<button type="button" class="close" data-dismiss="alert">&times;</button>
		PROBLEM: Dynamic storage is not writeable.
		<p>The dynamic directory is '.getConfigOption('dynamic_directory').'. Thenaterweb needs write access to this directory.</p>
		</div>';

}

# Check if log directory is writeable
if ( is_writable( GNAT_ROOT.'/var/log/writetest.txt') ){

	unlink( GNAT_ROOT.'/var/log/writetest.txt' );


	print '	<div class="alert alert-success">
		<button type="button" class="close" data-dismiss="alert">&times;</button>
		OK: Log storage is writeable.
		</div>';
}else{

	print '	<div class="alert alert-warning">
		<button type="button" class="close" data-dismiss="alert">&times;</button>
		PROBLEM: Log storage is not writeable.
		<p>The log directory is engine/var/log. Thenaterweb needs write access to this directory.</p>
		</div>';

}


?>