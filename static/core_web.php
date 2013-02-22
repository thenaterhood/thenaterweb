<?php

include '/home/natelev/www/static/core_config.php';

function safeChars($string, $length) {
	/*
	* Verify that a string is made of html-safe characters and
	* short enough to fit where it belongs.  Basically some simple
	* input sanitizing for nonsecure things.
	* 
	* Arguments:
	*  $string (string): a string or something else
	*  $length (integer): an integer value for the length limit of the string
	* 
	* Returns:
	*  $safestring (string): a html-safe and proper length string
	*/
	
	# Check that the string is actually a string, return "" if not
	if (gettype($string) != 'string'){
		return '';
	}
	
	#Santize input so that it's text so we don't have XSS problems
	$safestring = preg_replace('/[^a-zA-Z0-9\s.]/', '', $string);

	$saferstring = htmlspecialchars($safestring, ENT_QUOTES);
	
	#Check the length of the string and the limit given, truncate if needed
	if ($length == 0){
		return $saferstring;
	}
	if (strlen($saferstring) > $length){
		return substr($saferstring, 0, $length);
	}
	else {
		return $saferstring;
	}
}

function setIfEmpty($string, $emptyValue){
	/*
	* Checks if a given string is empty and returns the value to set
	* it as if it is.  if not, returns the string.
	*
	* Arguments:
	*	$string (string): string value to check
	*	$emptyValue (string): Value to return if the string is empty
	*
	* Returns:
	*	$string or $emptyValue (string): $string if the string is not empty
	*		or $emptyValue if the string is empty
	*/
	if (empty($string)){
		return $emptyValue;
	}
	else{
		return $string;
	}
}

function checkCookie($name, $emptyValue){
	/*
	* Checks the cookie with the given name and returns its contents,
	* or a default value if the cookie is empty/doesn't exist
	* 
	*
	* Arguments:
	*	$name (string): the name of the cookie to check
	*	$emptyValue (string): string to return if the cookie is bad
	*
	* Returns:
	*	$contents (string): the contents of the cookie or default value
	*/
	$contents = $_COOKIE[$name];
	
	return setIfEmpty(&$contents, &$emptyValue);
}

function setVarFromURL($name, $emptyValue, $length){
	/*
	* Sets a variable from the URL by running the URL input through
	* safeChars to make it html-safe and the right size, then
	* looking for a cookie if the variable has not been set, and 
	* sets the variable to a default value if it has not been defined
	* in the url or a cookie.
	*
	* Arguments:
	*	$name (string): the name of the variable to get/set
	*	$emptyValue (string): a default value for the variable if no
	*		other value can be found
	*	$length (int): a maximum length for the variable if pulled from URL
	*
	* Returns:
	*	(string): the default value or the value pulled from a cookie or URL
	*/
	$value = safeChars($_GET[$name], &$length);
	return setIfEmpty(&$value, checkCookie(&$name, &$emptyValue));
}

function chooseInclude($preferred, $secondary){
	/*
	* Checks to see if the preferred file exists, and if it does
	* returns it, otherwise it returns the secondary file, which ideally
	* should be a file (like an error page) that is guaranteed to exist.
	* 
	* Arguments:
	*  $preferred (string): the preferred page to include
	*  $secondary (string): the secondary, emergency page to include
	* 
	* Returns:
	*  $page (string): the page that should be included
	* 
	*/
	
	if (file_exists("$preferred")){
		return "$preferred";
	}
	else{
		return "$secondary";
	}
}

function randomGreeting($first_name){
	/*
	* Displays a string with a random greeting and the string
	* the function was called with.
	* 
	* Arguments:
	*  $first_name (str): a string, preferably a name
	* 
	* Returns:
	*  (str) a string with a personal greeting
	*/
	$greetings = array("Howdy", "Hello", "Hi", "Hey there", "Hi there");
	return $greetings[ array_rand($greetings) ].", $first_name";
	
}

function avoidFiles(){
	/*
	* Returns a list of files that should not be displayed
	* or linked to when dynamically finding and generating pages
	* with lists of files or links to files. This is now stored
	* in the global configuration ($webroot/static/core_config.json)
	* but the function is still here until everything else gets told
	* that.
	*/
	return getConfigOption('hidden_files');
 }
 
 function getConfigOption($key){
	
	$CONFIG = new config();
	return $CONFIG->$key;
 }
	

?>
