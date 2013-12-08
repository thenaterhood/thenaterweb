<?php
include 'engine/lib/core_sitemap.php';

$session = new session( array('regen', 'type', 'page') );
$config = new config();

$sitemap = createSitemap( $blogdef->getPageList() );

if ( $session->type == "html" ){
	print $sitemap->toHtml();
}
else{
	print $sitemap->toXml();
}

?>