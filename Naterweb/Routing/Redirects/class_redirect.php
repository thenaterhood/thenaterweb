<?php
/**
 * Provides a class for managing redirecting a page.
 * The class accepts a destination and origin and
 * allows for a redirect to be applied at any time, 
 * providing an advantage over using only PHP's 
 * builtin redirect resources.
 *
 * @author Nate Levesque <public@thenaterhood.com>
 */
namespace Naterweb\Routing\Redirects;
/**
 * Provides a class for storing and applying
 * a redirect to another area of a website.
 * 
 */
class Redirect{
	
	/**
	 * @var $origin - the page to redirect from
	 * @var $destination - the destination to redirect to
	 */
	protected $origin, $destination;
	
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
	public function view( $print=False ){
		if ( $print )
			print $this->origin." to ".$this->destination;
		return $this->origin." to ".$this->destination;
	}

	/**
	 * Provides a simple text output to check if the class
	 * was initiated correctly
	 * 
	 */
	public function toHtml( $showOrigin=false ){
		$r = "";
		if ( $showOrigin )
			$r = $r.$this->origin;

		$r = $r." to <a href='".$this->destination."'>".$this->destination."</a>.";

		return $r;
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
		
		header("HTTP/1.1 302 Moved Temporarily");

		header("Location: ".$this->destination);

		
	}
	
}
?>
