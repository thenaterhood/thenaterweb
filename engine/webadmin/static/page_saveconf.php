<?php

$updatedConf = $_POST['content'];
$confFile = $_POST['rcfile'];

if ( is_writable( $confFile ) ){
	$confClass = fopen( $confFile, 'w' );
	fwrite( $confClass, $updatedConf );
	fclose( $confClass );

	print '<h1>Updated Configuration Saved</h1>';
}
else{
	print '<h1>Error Saving Configuration</h1>';
	print '<p>Gnat does not have write access to the configuration file.</p>';
}

?>
<p><a href="index.php">Back to webadmin panel</a></p>