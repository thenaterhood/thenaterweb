<?php

namespace Naterweb\Client;

/**
 * Provides access and sanitation functions for 
 * retrieving data from PHP HTTP variables.
 * 
 * @author Nate Levesque <public@thenaterhood.com>
 */
class request{
    
        public static function variable( $varname ){
            $array = array();
            $array[] = $varname;
            
            $values = self::get_sanitized($array);
            
            return $values[$varname];
        }

	/**
         * Returns an array of variables retrieved 
         * and sanitized from (in order of precedence)
         * $_POST, $_GET, $_COOKIE and a default value
         * if configured.
         * 
         * @param type $varnames - an array of variable names
         * @return type - an array of cleaned variable values.
         */
	public static function get_sanitized( $varnames ){

		$sanitized = array();

		foreach( $varnames as $name ){

			$methods = array( 'sanitized_post', 'sanitized_get', 'sanitized_cookie', 'default_value' );
			$i = 0;
			$value = '';
			while ( $i < count( $methods ) and $value == '' ){
				$value = self::$methods[$i]($name);
				$i++;
			}

			$sanitized[ $name ] = $value;

		}

		return $sanitized;


	}

        /**
         * Returns an object with the requested variables 
         * cleaned. This is primarily to take the place of 
         * the session class from previous versions. It 
         * acts as a drop-in replacement. Uses the get_sanitized 
         * function internally then casts the array to an object.
         * 
         * @param type $varnames - an array of variable names to find
         * @return type - an stdClass instance containing the requested 
         *  variables as fields.
         */
	public static function get_sanitized_as_object( $varnames ){

		$sanitized = self::get_sanitized( $varnames );

		$object = ( object )$sanitized;

		return $object;

	}

        /**
         * Returns a sanitized variable value from the get 
         * array or an empty string if it doesn't exist.
         * 
         * @param type $varname - the name of the variable
         * @return string - the value of the variable
         */
	public static function sanitized_get( $varname ){

		if ( array_key_exists($varname, $_GET) ){

			return self::sanitize( $_GET[$varname] );

		} else {
			return '';
		}


	}

        /**
         * Returns a sanitized variable value from the post 
         * array or an empty string if it doesn't exist.
         * 
         * @param type $varname - the name of the variable
         * @return string - the value of the variable
         */
	public static function sanitized_post( $varname ){

		if ( array_key_exists($varname, $_POST)){

			return self::sanitize( $_POST[$varname] );

		} else {
			return '';
		}


	}

        /**
         * Returns a sanitized variable value from the cookie 
         * array or an empty string if it doesn't exist.
         * 
         * @param type $varname - the name of the variable
         * @return string - the value of the variable
         */
	public static function sanitized_cookie( $varname ){

		if ( array_key_exists($varname, $_COOKIE) ){

			return self::sanitize( $_COOKIE[$varname] );
		} else {
			return '';
		}

	}

        /**
         * Returns the default value for a variable name 
         * as configured in the settings.php file.
         * 
         * @param type $varname - the name of the variable
         * @return type - the default value
         */
	public static function default_value( $varname ){

		$conf = getConfigOption( $varname );
		return $conf[0];


	}

        /**
         * Returns meta information from the SERVER 
         * array or an empty string if it doesn't exist.
         * 
         * @param type $varname - the name of the variable
         * @return string - the value of the variable
         */
	public static function meta( $varname ){

		if ( array_key_exists($varname, $_SERVER) ){
			return $_SERVER[$varname];
		} else {
			return '';
		}


	}

        /**
         * Returns a raw value from the POST array 
         * without sanitizing it, or an empty string 
         * if it doesn't exist.
         * @param type $varname - the name of the variable
         * @return string - the value of the variable
         */
	public static function post( $varname ){

		if ( array_key_exists($varname, $_POST) ){
			return $_POST[$varname];
		} else {

			return '';
		}

	}

        /**
         * Returns a raw value from the GET array 
         * without sanitizing it, or an empty string 
         * if it doesn't exist.
         * @param type $varname - the name of the variable
         * @return string - the value of the variable
         */
	public static function get( $varname ){

		if ( array_key_exists($varname, $_GET) ){
			return $_GET[$varname];
		} else {

			return '';
		}


	}

        /**
         * Returns a raw value from the cookie array 
         * without sanitizing it, or an empty string 
         * if it doesn't exist.
         * @param type $varname - the name of the variable
         * @return string - the value of the variable
         */
	public static function cookie( $varname ){

		if ( array_key_exists($varname, $_COOKIE) ){
			return $_COOKIE[$varname];
		} else {

			return '';
		}


	}

	/**
	* Verify that a string is made of html-safe characters and
	* short enough to fit where it belongs.  Basically some simple
	* input sanitizing for nonsecure things.
	* 
	*/
	public static function sanitize( $dirty, $maxlength=0 ) {
		# Check that the string is actually a string, return "" if not
		if (gettype($dirty) != 'string'){
			return '';
		}
		
		#Santize input so that it's text so we don't have XSS problems
		$safestring = preg_replace('/[^a-zA-Z0-9\s.:-]/', '', $dirty);
	
		$saferstring = htmlspecialchars($safestring, ENT_QUOTES);
		
		#Check the length of the string and the limit given, truncate if needed
		if ($maxlength == 0){
			return $saferstring;
		}
		if (strlen($saferstring) > $maxlength){
			return substr($saferstring, 0, $maxlength);
		}

	}

}


?>
