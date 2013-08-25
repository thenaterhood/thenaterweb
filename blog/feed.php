<?php

include GNAT_ROOT.'/lib/core_feed.php';

$id = 'blog';
$session = new session( array('regen') );
$config = new config();
$blogdef = loadBlogConf( strtolower($id) );

$feed = generateFeed( $blogdef->id, $blogdef->title, $blogdef->catchline, $session->regen, $blogdef->post_directory );
print $feed->output( $config->feed_type );

?>
