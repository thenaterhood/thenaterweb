<?php

class request{

	public function __construct(){
		# pass
	}
	
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

	public static function get_sanitized_as_object( $varnames ){

		$sanitized = self::get_sanitized( $varnames );

		$object = ( object )$sanitized;

		return $object;

	}

	public static function sanitized_get( $varname ){

		if ( array_key_exists($varname, $_GET) ){

			return self::sanitize( $_GET[$varname] );

		} else {
			return '';
		}


	}

	public static function sanitized_post( $varname ){

		if ( array_key_exists($varname, $_POST)){

			return self::sanitize( $_POST[$varname] );

		} else {
			return '';
		}


	}

	public static function sanitized_cookie( $varname ){

		if ( array_key_exists($varname, $_COOKIE) ){

			return self::sanitize( $_COOKIE[$varname] );
		} else {
			return '';
		}

	}

	public static function default_value( $varname ){

		$conf = getConfigOption( $varname );
		return $conf[0];


	}

	public static function meta( $varname ){

		if ( array_key_exists($varname, $_SERVER) ){
			return $_SERVER[$varname];
		} else {
			return '';
		}


	}

	public static function post( $varname ){

		if ( array_key_exists($varname, $_POST) ){
			return $_POST[$varname];
		} else {

			return '';
		}

	}

	public static function get( $varname ){

		if ( array_key_exists($varname, $_GET) ){
			return $_GET[$varname];
		} else {

			return '';
		}


	}

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
	private static function sanitize( $dirty, $maxlength=0 ) {
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
		else {
			return $saferstring;
		}
	}

}


?>
