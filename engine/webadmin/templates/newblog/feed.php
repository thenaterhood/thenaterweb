<?php

include GNAT_ROOT.'/lib/core_feed.php';
include 'class_blogdef.php';

$session = new session( array('regen') );
$config = new config();
$blogdef = getBlogConf( $id );

$feed = generateFeed( $blogdef->id, $blogdef->title, $blogdef->catchline, $session->regen, $blogdef->post_directory );
print $feed->output( $config->feed_type );

?>
