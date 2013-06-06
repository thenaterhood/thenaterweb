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
include 'class_config.php';
include 'class_varGetter.php';
include 'class_session.php';
include 'class_lock.php';

/**
* Checks to see if the preferred file exists, and if it does
* returns it, otherwise it returns the secondary file, which ideally
* should be a file (like an error page) that is guaranteed to exist.
* 
* @param $preferred (string): the preferred page to include
* @param $secondary (string): the secondary, emergency page to include
* 
* 
*/
function getContent($preferred, $secondary){

	// Set up a default state, using the secondary.
	// Note that the secondary will not currently be searched
	// for, so must include an extension. It is assumed
	// it will be includable and will exist. If not, woe is you.
	$file = $secondary;
	$type = 'php';

	// If no file extension was given, initiate a search
	// for the file
	if ( !strpos( $preferred, '.' ) ){

		// Types supported by the class, in order of precedence
		$supportedTypes = array( 'php', 'html', 'pre' );

		// Search for the file in order of precedence
		$i = 0;
		while ( $i < count($supportedTypes) && !file_exists($preferred.'.'.$supportedTypes[$i]) ){

			// If the file exists, update the class with it and break
			if ( file_exists( $preferred.'.'.$supportedTypes[$i] ) ){
				$file = $preferred.'.'.$supportedTypes[$i];
				$type = $supportedTypes[$i];
			}

			++$i;

		}

	}
	else{
		if ( file_exists( $preferred ) ){
			$type = substr( $preferred, strpos( $preferred, '.')+1 );
			$file = $preferred;
		}
	}

	$r = array();
	$r['file'] = $file;
    // If the file may contain php, then simply include the file
	if ( $type == "php" || $type == "html" ){

		$r['pre'] = "";
		$r['post'] = "";
		$r['sanitize'] = False;

	}

	// If the file is of type pre (preformatted), insert the
	// tags and sterilize the contents
	else if ( $type == "pre" ){

		$r['pre'] = '<pre>';
		$r['post'] = '</pre>';
		$r['sanitize'] = True;
		
	}

	return $r;
}

function chooseInclude( $preferred, $secondary ){

	$contentInstructions = getContent( $preferred, $secondary );
	return $contentInstructions['file'];

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
