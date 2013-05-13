<?php

include_once 'class_dataMonger.php';
/**
* Manages retrieving and displaying page content
* in various forms.
*
* @since 5/12/2013
*/
class content extends dataMonger{
	
	/**
	 * Constructs an instance of the class given the id of
	 * a page and a session instance
	 * 
	 * @param $pageid - the page id to search for
	 * @param $session - an instance of the session class
	 */
	public function __construct($pageid, $session){

		// Sets up the default case where the file could not be found

		$this->container['title'] = $pageid;
		$this->container['contentfile'] = getConfigOption('webcore_root').'/template_error.php';
		$this->container['type'] = 'php';
		$this->container['session'] = $session;

		// Types supported by the class, in order of precedence
		$supportedTypes = array( 'php', 'html', 'pre');

		// Put together the main part of the filename
		$filename = getConfigOption('webcore_root')."/page_$pageid";

		// Search for the file in order of precedence
		for( $i = 0; $i < count( $supportedTypes ); ++$i ){

			// If the file exists, update the class with it and break
			if ( file_exists( $filename.'.'.$supportedTypes[$i] ) ){
				$this->container['contentfile'] = $filename.'.'.$supportedTypes[$i];
				$this->container['type'] = $supportedTypes[$i];
				break;
			}

		}

	}

	
	/**
	 * Returns a representation of the post in the format requested
	 * 
	 * @param $type - the type of feed
	 */
	public function dump(){
		
		return $this->container;
	}


	/**
	 * Displays the content by including it in the page
	 * for php/html formats and by printing it
	 * for non-formatted formats that will not contain
	 * php data.
	 */
	public function output(){

		$type = $this->container['type'];
		$this->$type();
	}

	/**
	 * Sets up a session object for the requested php page to use
	 * then includes the php page.
	 */
	private function php(){

		// Included to support legacy pages which rely
		// on access to the session
		$session = $this->container['session'];

		include $this->container['contentfile'];

	}

	/**
	 * Sets up a session object for the requested page to use
	 * in case the html contains php data, then includes
	 * the page.
	 */
	private function html(){

		// Included to support legacy pages which rely
		// on access to the session
		$session = $this->container['session'];

		include $this->container['contentfile'];

	}

	/**
	 * Displays preformatted text by adding <pre>
	 * tags around the data then printing the
	 * contents of the preformatted file
	 */
	private function pre(){

		// 
		print '<pre>';
		print htmlspecialchars( file_get_contents( $this->container['contentfile'] ) );
		print '</pre>';

	}

 }
 ?>