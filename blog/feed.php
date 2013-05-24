<?php

include '../engine/core_feed.php';

$session = new session( array('regen') );
$config = new config();

$feed = generateFeed( 'blog', 'The Philosophy of Nate', "It's the cyber age; stay in the know", $session->regen );
print $feed->output( $config->feed_type );

?>
