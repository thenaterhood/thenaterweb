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

include '/home/natelev/www/static/core_config.php';

class session{
	/*
	 * Provides a common interface for picking up variables from the
	 * user in a clean way, so that internal variables for pulling
	 * pages and otherwise can be managed more easily and sanitation
	 * settings are more easily applied site-wide.
	 */
	private $request, $varDefs;
	
	public function __construct($request){
		/*
		 * Iterates through all the variables requested in $request
		 * and sets them to their defaults or from the URL/cookie.
		 * 
		 * Arguments:
		 * 	$request (array): a list of variables to set
		 */		
		foreach( $request as $name){
			$varConf = getConfigOption($name);
			
			if ( $varConf ){
				$this->varDefs[$name] = $this->setVarFromURL( $name, $varConf[0], $varConf[1] );
			}
			else{
				$this->varDefs[$name] = $this->setVarFromURL( $name, '', 50 );
			}
		}
	}
	
	public function __get($field){
		/*
		 * Retrieves a value from the associative array.
		 * 
		 * Arguments:
		 * 	$field (str): the name of the variable to retrieve
		 * Returns:
		 * 	the value of the variable or '' if it doesn't exist
		 */
		return $this->varDefs[$field];
	}
	
	private function checkCookie($name, $emptyValue){
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
		
		return $this->setIfEmpty(&$contents, &$emptyValue);
	}

	private function setVarFromURL($name, $emptyValue, $length){
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
		$sanitized = new sanitation($_GET[$name], 'str', $length);
		return $this->setIfEmpty(&$sanitized->str, $this->checkCookie(&$name, &$emptyValue));
	}
	
	private function setIfEmpty($string, $emptyValue){
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
}

class sanitation{
	/*
	 * Manages sanitizing user input.  Currently works only for strings,
	 * but adding functions to sanitize other data types is trivial.
	 * Retrieving sanitized values from the class involves invoking
	 * the __get function with the type, str, arr, bool, etc for whichever
	 * sanitation functions are implemented. The sanitization for the
	 * requested return is called (returns an empty object if the 
	 * sanitization can't convert the current one to the requested one).
	 */
	private $dirty, $length, $type;
	
	public function __construct($rawVar, $type, $length){
		/*
		 * Constructs an instance of the class
		 * containing the original variable and the sanitized
		 * variable
		 */
		$this->dirty = $rawVar;
		$this->length = $length;
		$this->type = $type;
		
	}
	
	public function __get($type){
		/*
		 * Returns a sanitized version of the variable.  If the requested
		 * type is not the same as the actual type, the class will
		 * attempt to convert it to the type if possible. Note that
		 * list cannot become a string.
		 * 
		 * Arguments:
		 * 	$type: the type of data to return (str, bool, arr, etc)
		 * Returns:
		 * 	a sanitized string
		 */
		
		return $this->$type($this->dirty, $this->length);
	}
	
	private function str($string, $length) {
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
	$greetings = array("Howdy", "Hello", "Hi", "Hey there", "Hi there", "Greetings");
	return $greetings[ array_rand($greetings) ].", $first_name";
	
}
 
 function getConfigOption($key){
	/*
	 * Built to abstract retrieving config variables, since
	 * they're now contained in a class this is just for legacy
	 * support until everything else gets moved off
	 * of using this function
	 */
	
	$CONFIG = new config();
	return $CONFIG->$key;
 }
	

?>
