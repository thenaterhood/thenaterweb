<?php

include GNAT_ROOT.'/lib/core_feed.php';


Header('Content-type: application/atom+xml');


$session = new session( array('regen') );
$config = new config();

$feed = generateFeed( $blogdef->id, $blogdef->title, $blogdef->catchline, $session->regen, $blogdef->post_directory );
print $feed->output( $config->feed_type );

?>