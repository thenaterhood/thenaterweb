<?php

$updatedConf = $_POST['content'];

$confClass = fopen( '../class_config.php', 'w' );
fwrite( $confClass, $updatedConf );
fclose( $confClass );

?>
<h1>Updated Configuration Saved</h1>
<p><a href="index.php">Back to webadmin panel</a></p>