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
 * 
 * @return $sitemap (sitemap): an xml sitemap
 */
function createSitemap( $files ){

	$sitemap = new urlset();

	
	foreach ( $paths as $file => $uri ) {

		if ( !in_array( $file, getConfigOption('hidden_files') ) ){
			$last_modified = filemtime( $file );
			$sitemap->new_item( $uri, date(DATE_ATOM, $last_modified));
		}
	} 

	return $sitemap;	
}

?>
