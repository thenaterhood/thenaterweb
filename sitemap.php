<?php
include '/home/natelev/www/static/core_sitemap.php';
include '/home/natelev/www/static/core_web.php';

function createSitemap($localpath, $webpath, $delimeters){
	/*
	* Generates a sitemap given a list of local and web paths
	* which correspond to each other
	* 
	* Arguments:
	*  $localpath (list): a list of local paths to search for files in
	*  $webpath (list): a list of web addresses, which correspond to the localpaths
	*  $delimeters (list): a list of file prefixes to search for in the dir
	* 
	* Returns:
	*  $sitemap (sitemap): an xml sitemap
	*/
	$sitemap = new urlset();
	
	for ($i = 0; $i < count($localpath); $i++){
		$path = $localpath[$i];
		$dir = opendir("$path");
		$search = $delimeters[$i];
		while (false !== ($file = readdir($dir))) {

				if ( strpos($file, $search) === 0 and !in_array($file, avoidFiles()) ){
					$pageName = explode(".", substr($file,strpos($file, '_')+1) );
					$last_modified = filemtime("$path/$file");
					$sitemap->new_item("$webpath[$i]$pageName[0]", date(DATE_ATOM, $last_modified));
				}
		} 
	}
	
	return $sitemap;
	
	
}
$regen = setVarFromURL('regen', '', 4);
$autoregen = getConfigOption('auto_file_regen');
$dynamicLocation = getConfigOption('dynamic_directory');
$save = getConfigOption('save_dynamics');

if ($autoregen || $regen){
	/*
	* Decides whether or not to regenerate the sitemap, and saves
	* the generated sitemap if the config option is set.
	*/
	$pageDirectories = array( getConfigOption('webcore_root') );

	$sitemap = createSitemap($pageDirectories, array( getConfigOption('site_domain').'/?id=' ), array('page'));
	print $sitemap->output();
	
	if ($save){
		$file = fopen("$dynamicLocation/sitemap.xml", 'w');
		fwrite($file, $sitemap->output());
		fclose($file);
	}
}

else{
	/*
	* If the software decides the sitemap should not be regenerated
	* based on the config (and soon, if it is up to date) then it
	* includes the saved file if it exists. Otherwise, it regenerates
	* on the fly and prints the file without saving it.
	*/
	if ( file_exists("$dynamicLocation/sitemap.xml") ){
		print file_get_contents("$dynamicLocation/sitemap.xml");
	}
	else{
		$pageDirectories = array( getConfigOption('webcore_root') );

		$sitemap = createSitemap($pageDirectories, array( getConfigOption('site_domain').'/?id=' ), array('page') );
		print $sitemap->output();
	}
}


?>
