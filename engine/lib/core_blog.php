<?php
/**
 * Contains classes and functions for retrieving, displaying, and
 * managing blog posts and other aspects of the blog platform
 * 
 * @author Nate Levesque <public@thenaterhood.com>
 * Language: PHP
 * Filename: core_blog.php
 * 
 */

/**
 * Includes the core_web functions
 */
include_once GNAT_ROOT.'/lib/core_web.php';
include_once GNAT_ROOT.'/classes/class_article.php';

/**
* Returns a random line of a given file.
* Used mainly for generating random suggestions 
* for additional blog posts to read.
* 
* @param $filename - a filename to pull a line from
*/
function RandomLine($filename) {

	$lines = file($filename) ;
	return $lines[array_rand($lines)] ;
}

/**
 * Converts a SimpleXMLElement to an associative
 * array.
 * @param $xml - a SimpleXMLElement to convert to array
 * @return $array - an associative array of the SimpleXMLElement data
 */
function XmltoArray(SimpleXMLElement $xml) {
    $array = (array)$xml;
    
    foreach ( array_slice($array, 0) as $key => $value ) {
        if ( $value instanceof SimpleXMLElement ) {
            $array[$key] = empty($value) ? NULL : XmltoArray($value);
        }   
    }   
    return $array;
}   
    
/**
 * Converts an array to an stdclass object
 * @param $d - an associative array
 * @return $d - an std class object
 */
function arrayToObject($d) {
    if (is_array($d)) {
        /*
        * Return array converted to object
        * Using __FUNCTION__ (Magic constant)
        * for recursive call 
        */
        return (object) array_map(__FUNCTION__, $d);
    } else {  
        // Return object
        return $d;
    }       
}       

/**
 * Loads a section/blog configuration xml file
 * @param $id - the section id
 * @return $conf - an stdclass instance with the config data
 */       
function loadBlogConf( $id ){


	include_once 'controller/'.$id.'/main.php';
	define( strtoupper($id).'_ROOT', 'controller/'.$id );
	$blogController = new $id();
	$confFile = $blogController->configFile;

    $conf = array();
    $conf['title'] = "Error";
    $conf['catchline'] = "";
    $conf['commentCode'] = "";
    
    
    if ( file_exists($confFile) ){
            $xml = simplexml_load_file( $confFile );
            $conf = xmltoArray( $xml ); 
            
            
    }

    return arrayToObject($conf);

}

function loadApplication( $id ){

    include_once 'controller/'.$id.'/main.php';
    define( strtoupper($id).'_ROOT', 'controller/'.$id );
    

    return new $id();

}

function load_all_applications(){

    $controllers = getControllers();
    $initialized = array();

    foreach ($controllers as $c) {

        $initialized[] = loadApplication( $c );
    }

    return $initialized;


}


?>
