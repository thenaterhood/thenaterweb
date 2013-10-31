<?php
include 'engine/lib/core_sitemap.php';

$session = new session( array('regen', 'type', 'page') );
$config = new config();

$pageDirectories = array( $config->webcore_root );

$sitemap = createSitemap($blogdef->page_directory, $config->site_domain.'/'.$blogdef->id );

if ( $session->type == "html" ){
	print $sitemap->toHtml();
}
else{
	print $sitemap->toXml();
}

?>