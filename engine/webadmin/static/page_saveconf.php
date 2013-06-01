<?php

$updatedConf = $_POST['content'];
$confFile = $_POST['rcfile'];

$confClass = fopen( $confFile, 'w' );
fwrite( $confClass, $updatedConf );
fclose( $confClass );

?>
<h1>Updated Configuration Saved</h1>
<p><a href="index.php">Back to webadmin panel</a></p>