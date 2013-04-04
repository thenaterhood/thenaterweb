<?php
/*
* Author: Nate Levesque <public@thenaterhood.com>
* Language: PHP
* Filename: core_web.php
* 
* Description:
* 	Contains functions for basic web capabilities such as reading
* 	variables from the URL (safely), setting/getting cookies and config
* 	options.
*/

include 'core_config.php';

/**
 * Provides a common interface for picking up variables from the
 * user in a clean way, so that internal variables for pulling
 * pages and otherwise can be managed more easily and sanitation
 * settings are more easily applied site-wide.
 */
class session{
	
	private $request, $varDefs;
	
	/**
	 * Iterates through all the variables requested in $request
	 * and sets them to their defaults or from the URL/cookie.
	 * 
	 * @param $request (array) - a list of variables to retrieve
	 * 
	 */	
	public function __construct($request){
	
		foreach( $request as $name){
			$varConf = getConfigOption($name);
			
			if ( $varConf ){
				$this->varDefs[$name] = $this->setVarFromURL( $name, $varConf[0], $varConf[1] );
			}
			else{
				$this->varDefs[$name] = $this->setVarFromURL( $name, '', 50 );
			}
		}
		
		$this->varDefs["domain"] = $_SERVER['HTTP_HOST'];
		$this->varDefs["uri"] = $_SERVER['REQUEST_URI'];
		$this->varDefs["referrer"] = $_SERVER['HTTP_REFERER'];
	}
	
	/**
	 * Retrieves a value from the associative array.
	 * 
	 * @param $field (str) - the name of a variable to retrieve
	 * 
	 * @return (str) - the value of the variable or '' if nonexistant
	 * 
	 */
	public function __get($field){

		return $this->varDefs[$field];
	}
	
	/**
	* Checks the cookie with the given name and returns its contents,
	* or a default value if the cookie is empty/doesn't exist
	* 
	* @param $name (string) - the name of the cookie to check
	* @param $emptyValue (string) - string to return if the cookie is bad
	*
	* @return $contents (string)- the contents of the cookie or default value
	*/
	private function checkCookie($name, $emptyValue){

		$contents = $_COOKIE[$name];
		
		return $this->setIfEmpty($contents, $emptyValue);
	}

	/**
	* Sets a variable from the URL by running the URL input through
	* safeChars to make it html-safe and the right size, then
	* looking for a cookie if the variable has not been set, and 
	* sets the variable to a default value if it has not been defined
	* in the url or a cookie.
	*
	* @param $name (string): the name of the variable to get/set
	* @param $emptyValue (string): a default value for the variable if no
	* other value can be found
	* @param $length (int): a maximum length for the variable if pulled from URL
	*
	* @return (string): the default value or the value pulled from a cookie or URL
	*/
	private function setVarFromURL($name, $emptyValue, $length){

		$sanitized = new sanitation($_GET[$name], 'str', $length);
		return $this->setIfEmpty($sanitized->str, $this->checkCookie($name, $emptyValue));
	}
	
	/**
	* Checks if a given string is empty and returns the value to set
	* it as if it is.  if not, returns the string.
	*
	* @param $string (string): string value to check
	* @param $emptyValue (string): Value to return if the string is empty
	*
	* @return $string or $emptyValue (string): $string if the string is not empty
	*		or $emptyValue if the string is empty
	*/
	private function setIfEmpty($string, $emptyValue){

		if (empty($string)){
			return $emptyValue;
		}
		else{
			return $string;
		}
	}
	
	/**
	 * Dumps the contained session data as an associative array
	 * 
	 * @param - unused
	 * @return - the session data
	 */
	public function dump(){
		
		return $this->varDefs;
		
	}
}

/**
 * Manages sanitizing user input.  Currently works only for strings,
 * but adding functions to sanitize other data types is trivial.
 * Retrieving sanitized values from the class involves invoking
 * the __get function with the type, str, arr, bool, etc for whichever
 * sanitation functions are implemented. The sanitization for the
 * requested return is called (returns an empty object if the 
 * sanitization can't convert the current one to the requested one).
 */
class sanitation{

	private $dirty, $length, $type;
	
	/**
	 * Constructs an instance of the class
	 * containing the original variable and the sanitized
	 * variable
	 * 
	 * @param $rawVar (str) - the raw variable contents
	 * @param $type (str) - the desired type for the variable to be
	 * @param $length (int) - a maximum length for the variable
	 * 
	 */
	public function __construct($rawVar, $type, $length){

		$this->dirty = $rawVar;
		$this->length = $length;
		$this->type = $type;
		
	}
	
	/**
	 * Returns a sanitized version of the variable.  If the requested
	 * type is not the same as the actual type, the class will
	 * attempt to convert it to the type if possible. Note that
	 * list cannot become a string.
	 * 
	 * @param $type: the type of data to return (str, bool, arr, etc)
	 * 
	 * @return - a sanitized string
	 * 
	 */
	public function __get($type){
		
		return $this->$type($this->dirty, $this->length);
	}
	
	/**
	* Verify that a string is made of html-safe characters and
	* short enough to fit where it belongs.  Basically some simple
	* input sanitizing for nonsecure things.
	* 
	* @param $string (string): a string or something else
	* @param $length (integer): an integer value for the length limit of the string
	* 
	* @return $safestring (string): a html-safe and proper length string
	*/
	private function str($string, $length) {
		
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
}

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
