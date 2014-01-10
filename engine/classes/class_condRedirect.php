<?php
/**
 * Provides a means of redirecting a page conditionally
 * based on whether the destination is where the user
 * currently is or not.
 * @author Nate Levesque <public@thenaterhood.com>
 */

/**
 * Include the extended redirect class
 */

include_once NWEB_ROOT.'/classes/class_redirect.php';

/**
 * Manages conditionally redirecting pages based
 * on the requested uri and a given uri to redirect from.
 * Checks to see if the uri requested by the user contains
 * the uri to redirect from, and prepares to perform the
 * redirect if so.
 * 
 * Relies on the redirect class to manage the actual redirecting, 
 * this class is primarly an interface to it that deals with the
 * conditional aspect.
 */
class ConditionalRedirect extends Redirect{
	
	/**
	 * @var $apply - whether or not the redirect needs to occur
	 */
	 
	private $apply;	
	/**
	 * Creates an instance of the conditional redirect class
	 * and decides whether the redirect will be performed
	 * 
	 * @param $origin - a chosen uri to redirect from
	 * @param $destination - a uri to redirect to
	 * @param $uri - the uri requested by the user
	 */
	public function __construct( $origin, $destination, $uri ){
		
		$this->origin = $origin;
		$this->destination = $destination;
		
		if ( is_int( strpos($uri, $origin) ) && $origin != $destination ){

			$this->apply = True;
		}	
	}
	
	/**
	 * Applies the redirect to the page if the redirect should happen
	 * 
	 * @param $type - the type of redirect to apply
	 */
	public function apply( $type ){
		
		if ( $this->apply ){
			parent::apply( $type );
		}		
		
	}
	
}

?>
