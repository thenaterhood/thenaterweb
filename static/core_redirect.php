<?php
/*
* Author: Nate Levesque <public@thenaterhood.com>
* Language: PHP
* Filename: core_redirect.php
* 
* Description:
* 	Manages redirecting (conditionally and not) for 301 and 302 redirects
* 
*/

/**
 * Provides an interface for redirecting pages
 * 
 */
class redirect{
	
	private $origin, $destination;
	
	/**
	 * Produces an instance of the class
	 * 
	 * @param $origin - the url accessed originally
	 * @param $destination - the url to redirect to
	 */
	public function __construct($origin, $destination){
		$this->origin = $origin;
		$this->destination = $destination;
	}
	
	/**
	 * Provides a simple text output to check if the class
	 * was initiated correctly
	 * 
	 * @param - unused
	 */
	public function test(){
		print $this->origin." to ".$this->destination;
	}
	
	/**
	 * Applies the redirect to the page
	 * 
	 * @param $type - the type of redirect to use
	 */
	public function apply( $type ){
		
		if ( $type == 301 ){
			$this->apply_301();
		}
		if ( $type == 302 ){
			$this->apply_302();
		}
		
	}
	
	/**
	 * Performs a 301 (permanent) redirect
	 * 
	 * @param - unused
	 */
	private function apply_301(){
		
		header("HTTP/1.1 301 Moved Permanently");
		header("Location: ".$this->destination);
		
	}
	
	/**
	 * Performs a 302 (temporary) redirect
	 * 
	 * @param - unused
	 */
	private function apply_302(){
		
		header("Location: ".$this->destination);
		
	}
	
}

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
