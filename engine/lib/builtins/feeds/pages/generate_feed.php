<?php

Header('Content-type: application/atom+xml');


$session = new session( array('regen') );
$config = new config();

$feed = generateFeed( $blogdef, False );
print $feed->output( getConfigOption('feed_type') );

?>
