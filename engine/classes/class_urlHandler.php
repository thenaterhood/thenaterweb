<?php
/**
 * Contains functionality for handling and 
 * routing URLs
 *
 * @author Nate Levesque <public@thenaterhood.com>
 *
 */

/**
 * Include the main blog functionality
 */
include_once NWEB_ROOT.'/lib/core_blog.php';

/**
 * A class for managing URL related data
 */
class urlHandler{

	private $url;
	private $urlArray;
	private $controller;
	private $controllerId;

	/**
	 *
	 */
	public function __construct(){

		if ( isset($_GET['url'] ) ){
			$this->url = $_GET['url'];
			$this->urlArray = explode( '/', $this->url );

		} else {
			$this->url = 'page/home';
			$this->urlArray = explode('/', $this->url );
		}

		$this->parseUrl();
		$this->selectController();


	}

	/**
	 * Reparses the stored url data after removing 
	 * the first element from it
	 */
	public function reparseUrl(){

		unset( $this->urlArray[0] );
		$this->urlArray = array_values($this->urlArray);

		$this->parseUrl();
		$this->selectController();

	}

	/**
	 * Parses a URL directly and adds the 
	 * variables to the GET array.
	 */
	private function parseUrl(){

		$requestUri = $this->urlArray;
		#unset( $requestUri[0] );
		#print_r( $requestUri );

		$_GET['id'] = '';
		$_GET['controller'] = 'page';

		$requestUri = array_values( $requestUri );

		$arraySize = count( $requestUri );


		if ( $arraySize >= 1 )

			if ( $requestUri[0] != '' )
				$_GET['controller'] = array_shift( $requestUri );

			if ( $arraySize >= 2 )
				if ( $requestUri[0] != '' )
					$_GET['id'] = array_shift( $requestUri );

				if ( $arraySize > 2 ){


					for( $i = 0; $i < count( $requestUri ); $i+=2 ){
						$value = "";
						$key = $requestUri[$i];
						if ( $i+1 < count( $requestUri ) ){
							$j = $i + 1;
							$value = $requestUri[$j];

						}

						$_GET[$key] = $value;
					}

				}

				$sessionMvc = request::get_sanitized_as_object( array( 'controller' ) );
				$this->controllerId = $sessionMvc->controller;



			}

	/**
	 * Locates the controller for the url requested 
	 * or defaults to the error controller if it doesn't 
	 * exist.
	 */
	private function selectController(){

		$this->controller = "controller/".$this->controllerId."/main.php";

	}

	/**
	 * Returns the selected controller
	 * @return the name of the controller file
	 */
	public function getController(){
		return $this->controller;
	}

	/**
	 * Returns the controller ID 
	 * @return the name of the controller
	 */
	public function getControllerId(){
		return $this->controllerId;
	}
	

}

?>