<?php
/**
 * Provides a class for redirecting pages
 * 
 */
class redirect{
	
	/**
	 * @var $origin - the page to redirect from
	 * @var $destination - the destination to redirect to
	 */
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
	 */
	private function apply_301(){
		
		header("HTTP/1.1 301 Moved Permanently");
		header("Location: ".$this->destination);
		
	}
	
	/**
	 * Performs a 302 (temporary) redirect
	 * 
	 */
	private function apply_302(){
		
		header("Location: ".$this->destination);
		
	}
	
}
?>