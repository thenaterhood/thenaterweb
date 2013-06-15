<?php
include 'engine/lib/core_sitemap.php';

$session = new session( array('regen') );
$config = new config();

$pageDirectories = array( $config->webcore_root );

$sitemap = createSitemap($pageDirectories, array( $config->site_domain.'/?id=' ), array('page'));
print $sitemap->output();

// if ( $config->auto_file_regen || $session->regen ){
// 	/*
// 	* Decides whether or not to regenerate the sitemap, and saves
// 	* the generated sitemap if the config option is set.
// 	*/
	
// 	if ( $config->save_dynamics ){
// 		$file = fopen("$config->dynamic_directory/sitemap.xml", 'w');
// 		fwrite($file, $sitemap->output());
// 		fclose($file);
// 	}
// }

// else{
	
// 	* If the software decides the sitemap should not be regenerated
// 	* based on the config (and soon, if it is up to date) then it
// 	* includes the saved file if it exists. Otherwise, it regenerates
// 	* on the fly and prints the file without saving it.
	
// 	if ( file_exists("$config->dynamic_directory/sitemap.xml") ){
// 		print file_get_contents("$config->dynamic_directory/sitemap.xml");
// 	}
// 	else{
// 		$pageDirectories = array( $config->webcore_root );

// 		$sitemap = createSitemap($pageDirectories, array( $config->site_domain.'/?id=' ), array('page') );
// 		print $sitemap->output();
// 	}
// }


?>
