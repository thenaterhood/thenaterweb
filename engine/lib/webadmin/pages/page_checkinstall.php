<h1>Installation Details</h1>

<?php


echo '<h3>Controllers known to Thenaterweb</h3>';

$found = array();
$handler = opendir(GNAT_ROOT.'/config/section.d');
print '<ul>';

while ($file = readdir($handler)){

	if ( strpos( $file, '.conf.xml' ) && !in_array($file, $found) ){
		$blogid=substr($file, 0, strpos($file, ".") );
		$found[] = $file;
		print '<li><a href="'.getConfigOption('site_domain').'/webadmin/editblog/blogid/'.$blogid.'">'.$blogid.'</a></li>'."\n";
	}
}
print '</ul>';
print '<br />';

$writetest = fopen( GNAT_ROOT.'/var/dynamic/writetest.txt', 'w');
fclose($writetest);

# Check if dynamic directory is writeable
if ( is_writable( GNAT_ROOT.'/var/dynamic/writetest.txt') ){

	unlink( GNAT_ROOT.'/var/dynamic/writetest.txt' );
	echo '<h4><font color="green">OK: Dynamic storage is writeable.</font></h4>';
}else{

	echo '<h4><font color="red">PROBLEM: Dynamic storage is not writeable.</font></h4>';
	echo '<p>The dynamic directory is '.getConfigOption('dynamic_directory').'. Thenaterweb needs write access to this directory.</p>';

}

# Check if log directory is writeable
if ( is_writable( GNAT_ROOT.'/var/dynamiclog/writetest.txt') ){

	unlink( GNAT_ROOT.'/var/dynamiclog/writetest.txt' );
	echo '<h4><font color="green">OK: Log storage is writeable.</font></h4>';
}else{

	echo '<h4><font color="red">PROBLEM: Log storage is not writeable.</font></h4>';
	echo '<p>The log directory is engine/var/log. Thenaterweb needs write access to this directory.</p>';

}


?>