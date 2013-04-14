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
include 'core_config.php';

/**
 * Provides an abstract class to define a standard way
 * of managing data for the classes that are managing
 * things like session data, posts, etc.
 * 
 * @since 4/13/2013
 */
 abstract class dataMonger{
	 
	 /**
	  * @var $container - a container for data, preferrably assoc array
	  */
	 protected $container;
	 
	 /**
	  * Returns the contents of the container
	  */
	 public function dump(){
		 
		 return $this->container;
		 
	 }
	 
	 /**
	  * Retrieves a field from the container, assuming the container
	  * is an associative array.
	  * 
	  * @param $name - the item to retrieve
	  */	 
	 public function __get( $name ){
		 
		 return $this->container[$name];
		 
	 }
	 
	 /**
	  * Defines an abstract function to deal with outputting 
	  * the data in different forms
	  * 
	  * @param $type - the type of output desired. Depends on the
	  * facilities offered by the inheriting class.
	  */
	 abstract protected function output( $type );

	 
 }

/**
 * Provides a common interface for picking up variables from the
 * user in a clean way, so that internal variables for pulling
 * pages and otherwise can be managed more easily and sanitation
 * settings are more easily applied site-wide.
 * 
 */
class session extends dataMonger{
	
	/**
	 * @var $request (array) - the variables to be contained
	 * @var $varDefs (assoc. array) - the variables and assignments
	 */
	private $varDefs;
	
	/**
	 * Iterates through all the variables requested in $request
	 * and sets them to their defaults or from the URL/cookie.
	 * 
	 * @param $request (array) - a list of variables to retrieve
	 * 
	 */	
	public function __construct($request){
	
		foreach( $request as $name ){

			$varGetter = new varGetter( $name );				// Retrieve the variable
			$this->container[$name] = $varGetter->str;			// Store the variable in the session
			unset( $varGetter );								// Destroy the varGetter object
		}
		
		$this->container["domain"] = $_SERVER['HTTP_HOST'];
		$this->container["uri"] = $_SERVER['REQUEST_URI'];
		$this->container["referrer"] = $_SERVER['HTTP_REFERER'];
	}
	
	public function output( $type ){
		
		/*
		 * The session class doesn't need to have an output type
		 * for the moment
		 */
		return '';
		
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

	/**
	 * @var $dirty (str) - the string retrieved
	 * @var $length (int) - the maximum allowed length of the string
	 */
	protected $dirty, $length;
	
	/**
	 * Constructs an instance of the class
	 * containing the original variable and the sanitized
	 * variable
	 * 
	 * @param $rawVar (str) - the raw variable contents
	 * @param $length (int) - a maximum length for the variable
	 * 
	 */
	public function __construct($rawVar, $length){

		$this->dirty = $rawVar;
		$this->length = $length;
		
	}
	
	/**
	 * Returns a sanitized version of the variable.  If the requested
	 * type is not the same as the actual type, the class will
	 * attempt to convert it to the type if possible. Note that
	 * list cannot become a string.
	 * 
	 * @param $type - the type of data to return (str, bool, arr, etc)
	 * 
	 * @return - a sanitized variable
	 * 
	 */
	public function __get($type){
		
		return $this->$type();
	}
	
	/**
	* Verify that a string is made of html-safe characters and
	* short enough to fit where it belongs.  Basically some simple
	* input sanitizing for nonsecure things.
	* 
	*/
	private function str() {
		# Check that the string is actually a string, return "" if not
		if (gettype($this->dirty) != 'string'){
			return '';
		}
		
		#Santize input so that it's text so we don't have XSS problems
		$safestring = preg_replace('/[^a-zA-Z0-9\s.]/', '', $this->dirty);
	
		$saferstring = htmlspecialchars($safestring, ENT_QUOTES);
		
		#Check the length of the string and the limit given, truncate if needed
		if ($this->length == 0){
			return $saferstring;
		}
		if (strlen($saferstring) > $this->length){
			return substr($saferstring, 0, $this->length);
		}
		else {
			return $saferstring;
		}
	}
	
	/**
	 * Return a simple boolean based on the variable
	 */
	private function boo(){
		
		if ( $this->dirty and $this->dirty != "False" ){
			return True;
		}
		else{ 
			return False; 
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
 * Provides an interface for retrieving variables using a specific
 * method or by searching through each method to find a variable.
 */
class varGetter extends sanitation{
	 
	 /**
	  * Constructs an instance of the class and finds the variable
	  * 
	  * @param $name - the name of the variable
	  * @param $length (optional) - the allowed length of the variable
	  * @param $method (optional) - the method to use
	  */	 
	 public function __construct( $name ){
		 
		 if ( ! $length ){ // If no length specified, find the default
			 $conf = getConfigOption( $name );
			 $length = $conf[1];
		 }
		 if ( ! $length ){ // If still no length, default to 50
			 $length = 50;
		 }
		 
		 $this->length = $length;		 

		 if ( ! $method ){ // If no method is specified, try all of them
			 $methods = array( post, get, cookie, fallback );
			 $i = 0;
			 while ( $i < count( $methods ) and $this->dirty == null ){
				 $this->$methods[$i]($name);
				 $i++;
			 }
		 }
		 else{
			 $this->$method( $name );
		 }
		 
		 
	 }
	 
	 /**
	  * Retrieves a variable via post
	  * @param $name - the name of the variable
	  */
	 private function post( $name ){
		 $this->dirty = $_POST[ $name ];
	 }
	 
	 /**
	  * Retrieves a variable via get
	  * @param $name - the name of the variable
	  */
	 private function get( $name ){
		 $this->dirty = $_GET[ $name ];
	 }
	 
	 /**
	  * Retrieves a variable via a cookie
	  * @param $name - the name of the variable
	  */
	 private function cookie( $name ){
		 $this->dirty = $_COOKIE[ $name ]; 
	 }
	 
	 /**
	  * Retrieves a variable via the default
	  * @param $name - the name of the variable
	  */
	 private function fallback( $name ){
		 $conf = getConfigOption( $name );
		 $this->dirty = $conf[0];
	 }
	 
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
