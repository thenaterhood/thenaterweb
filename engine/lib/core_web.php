<?php
/**
 * 
 * Contains functions for basic web capabilities such as reading
 * variables from the URL (safely), setting/getting cookies and config
 * options.
 * @author Nate Levesque <public@thenaterhood.com>
 * @copyright Nate Levesque 2013
 * Language: PHP
 * Filename: core_web.php
 * 
 */

/**
 * Include the config file
 */
include 'settings.php';
include GNAT_ROOT.'/classes/class_varGetter.php';
include GNAT_ROOT.'/classes/class_session.php';
include GNAT_ROOT.'/classes/class_lock.php';
include GNAT_ROOT.'/lib/core_extension.php';
include GNAT_ROOT.'/classes/class_article.php';
include GNAT_ROOT.'/classes/class_urlHandler.php';
include GNAT_ROOT.'/lib/core_database.php';

/**
* Checks to see if the preferred file exists, and if it does
* returns it, otherwise it returns the secondary file, which ideally
* should be a file (like an error page) that is guaranteed to exist.
* 
* @param $preferred (string): the preferred page to include
* 
* 
*/
function pullContent( $preferred, $sectionUri='/', $articleUri='/' ){

	if ( ! is_array($preferred ) ){
		$to = array();
		$to[] = $preferred;
		$preferred = $to;
	}

	$i = 0;
	$article = new article( "", $sectionUri, $articleUri, False );

	while ( $i < count($preferred) && $article->getType() == "none" ){

		if ( !strpos( $preferred[$i], '.' ) ){

			$file = $preferred[$i];

		}
		else{
			$file = substr( $preferred[$i], 0, strpos( $preferred[$i], '.')-1);
		}

		$article = new article( $file, $sectionUri, $articleUri, False );

		$i++;

	}

	return $article;
}

/**
* Displays a string with a random greeting and the string
* the function was called with.
* 
* @param $first_name (str): a string, preferably a name
* 
* @return - a string with a personal greeting
*/
function randomGreeting($first_name){

	$greetings = array("Howdy", "Hello", "Hi", "Hey there", "Hi there", "Greetings", "Hiya");
	return $greetings[ array_rand($greetings) ].", $first_name";
	
}

function getControllers(){

	$found = array();
	$handler = opendir( 'controller' );

	while ($file = readdir($handler)){

		if ( $file != '.' && $file != '..' && !in_array($file, $found) && file_exists( 'controller/'.$file.'/main.php')){
			$blogid=substr($file, 0, strpos($file, ".") );
			$found[] = $file;
		}
	}

	return $found;

}

/**
 * Built to abstract retrieving config variables, since
 * they're now contained in a class this is just for legacy
 * support until everything else gets moved off
 * of using this function
 * 
 * @param $key - the name of a config key to retrieve
 * 
 * @return - the value of the config key
 */
function getConfigOption($key){
	
	$CONFIG = new config();
	return $CONFIG->$key;
}
	

?>
