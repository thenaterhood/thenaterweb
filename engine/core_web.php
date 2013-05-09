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

/**
* Checks to see if the preferred file exists, and if it does
* returns it, otherwise it returns the secondary file, which ideally
* should be a file (like an error page) that is guaranteed to exist.
* 
* @param $preferred (string): the preferred page to include
* @param $secondary (string): the secondary, emergency page to include
* 
* @return $page (string): the page that should be included
* 
*/
function chooseInclude($preferred, $secondary){
	
	if (file_exists("$preferred")){
		return "$preferred";
	}
	else{
		return "$secondary";
	}
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
