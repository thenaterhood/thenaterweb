<?php

include NWEB_ROOT.'/lib/core_feed.php';


Header('Content-type: application/atom+xml');


$session = request::get_sanitized_as_object( array('regen') );
$config = new config();

$feed = generateFeed( $blogdef->id, $blogdef->title, $blogdef->catchline, $session->regen, $blogdef->post_directory );
print $feed->output( $config->feed_type );

?>
