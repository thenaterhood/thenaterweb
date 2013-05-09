<?php
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
	
	private function num(){
		
		return (int) $this->dirty;
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
?>