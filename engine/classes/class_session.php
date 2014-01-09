<?php
/**
 * Class file for the dataMonger class
 * @author Nate Levesque <public@thenaterhood.com
 * @copyright 2013 Nate Levesque (TheNaterhood)
 * @since 5/8/2013
 */

/**
 * Include the dataMonger abstract class
 */
include_once NWEB_ROOT.'/classes/class_dataMonger.php';
include_once NWEB_ROOT.'/classes/class_varGetter.php';

/**
 * Provides a common interface for picking up variables from the
 * user in a clean way, so that internal variables for pulling
 * pages and otherwise can be managed more easily and sanitation
 * settings are more easily applied site-wide.
 *
 * @deprecated - deprecated in favor of the request class
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

		if ( isset( $_SERVER['HTTP_REFERER'] ) )
			$this->container["referer"] = $_SERVER['HTTP_REFERER'];
		else
			$this->container["referer"] = "-";
	}
}
?>