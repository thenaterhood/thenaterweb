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
	echo '<h4><font color="green">OK: Dynamic storage is writeable.</font></h4>';
}else{

	echo '<h4><font color="red">PROBLEM: Dynamic storage is not writeable.</font></h4>';
	echo '<p>The dynamic directory is '.getConfigOption('dynamic_directory').'. Thenaterweb needs write access to this directory.</p>';

}

# Check if log directory is writeable
if ( is_writable( GNAT_ROOT.'/var/log/writetest.txt') ){

	unlink( GNAT_ROOT.'/var/log/writetest.txt' );
	echo '<h4><font color="green">OK: Log storage is writeable.</font></h4>';
}else{

	echo '<h4><font color="red">PROBLEM: Log storage is not writeable.</font></h4>';
	echo '<p>The log directory is engine/var/log. Thenaterweb needs write access to this directory.</p>';

}


?>