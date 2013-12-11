<?php
/**
 * Saves an updated configuration file
 * @author Nate Levesque <public@thenaterhood.com>
 * @since 06/04/2013
 */
$updatedConf = $_POST['content'];
$confFile = $_POST['rcfile'];

$lock = new lock( $confFile );

if ( is_writable( $confFile ) && !$lock->isLocked() ){

	$lock->lock();

	$confClass = fopen( $confFile, 'w' );
	fwrite( $confClass, $updatedConf );
	fclose( $confClass );

	$lock->unlock();

	print '<h1>Updated Configuration Saved</h1>';
}
else{
	print '<h1>Error Saving Configuration</h1>';
	print '<p>Thenaterweb cannot currently write to the file: '. $confFile.'.</p>';
}

?>
<p><a href="<?php print getConfigOption('site_domain'); ?>/webadmin">Back to webadmin panel</a></p>