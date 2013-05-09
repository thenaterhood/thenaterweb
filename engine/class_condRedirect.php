<?php

include_once 'class_redirect.php';
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
class condRedirect extends redirect{
	
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
		
		parent::__construct( $uri, $destination );
				
		if ( strpos($uri, $origin) ){
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