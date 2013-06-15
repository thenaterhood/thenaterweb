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
 include $_SERVER['DOCUMENT_ROOT'].'/engine/lib/core_web.php';
 include $_SERVER['DOCUMENT_ROOT'].'/engine/classes/class_urlset.php';

/**
 * Generates a sitemap given a list of local and web paths
 * which correspond to each other
 * 
 * @param $localpath (list): a list of local paths to search for files in
 * @param $webpath (list): a list of web addresses, which correspond to the localpaths
 * @param $delimeters (list): a list of file prefixes to search for in the dir
 * 
 * @return $sitemap (sitemap): an xml sitemap
 */
function createSitemap($localpath, $webpath, $delimeters){

	$sitemap = new urlset();
	
	for ($i = 0; $i < count($localpath); $i++){
		$path = $localpath[$i];
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
	
	return $sitemap;	
}

?>
