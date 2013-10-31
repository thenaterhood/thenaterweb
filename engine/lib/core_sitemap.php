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
function createSitemap($includePath, $webpath){

	$sitemap = new urlset();

	
	$dir = opendir("$includePath");
	while ( $file = readdir($dir) ) {

			if ( !in_array( $file, getConfigOption('hidden_files') ) and substr($file, 0, 6) != 'hidden_' ){
				$pageName = explode(".", substr($file,strpos($file, '_')+1) );
				$last_modified = filemtime("$includePath/$file");
				$sitemap->new_item("$webpath/$pageName[0]", date(DATE_ATOM, $last_modified));
			}
	} 

	return $sitemap;	
}

?>
