<?php
/**
 * Contains classes for constructing an xml sitemap
 * 
 * @author Nate Levesque <public@thenaterhood.com>
 * @copyright Nate Levesque 2013
 * 
 * Language: PHP
 * Filename: core_sitemap.php
 * 
 */

/**
 * Include classes and functions from core_web
 */
 include_once GNAT_ROOT.'/lib/core_web.php';
 include_once GNAT_ROOT.'/classes/class_urlset.php';

/**
 * Generates a sitemap given a list of local and web paths
 * which correspond to each other
 * 
 * @param $includePaths (list): a list of local paths to search for files in
 * @param $webpath (list): a list of web addresses, which correspond to the includePathss
 * @param $delimeters (list): a list of file prefixes to search for in the dir
 * 
 * @return $sitemap (sitemap): an xml sitemap
 */
function createSitemap($includePaths, $webpath, $delimeters){

	$sitemap = new urlset();
	
	for ($i = 0; $i < count($includePaths); $i++){
		$path = $includePaths[$i];
		$dir = opendir("$path");
		$search = $delimeters[$i];
		while ( $file = readdir($dir) ) {

				if ( strpos($file, $search) === 0 and !in_array( $file, getConfigOption('hidden_files') ) ){
					$pageName = explode(".", substr($file,strpos($file, '_')+1) );
					$last_modified = filemtime("$path/$file");
					$sitemap->new_item("$webpath[$i]$pageName[0]", date(DATE_ATOM, $last_modified));
				}
		} 
	}

	/*

	$varDirectory = opendir( GNAT_ROOT.'/var/dynamic' );
	while ( $file = readdir( $varDirectory ) ){
		if ( strpos( $file, "inventory.json" ) ){
			$inventoryData = json_decode( file_get_contents(GNAT_ROOT.'/var/dynamic/'.$file, True), True );
			$meta = $inventoryData['metadata'];
			$lastmod = $meta['updated'];
			$sitemapUrl = $meta['sitemap'];
			if ( ! is_null($sitemapUrl) && ! is_null($lastmod ) ){
				$sitemap->new_item( $sitemapUrl, $lastmod );
			}
		}
	}
	*/



	
	return $sitemap;	
}

?>
