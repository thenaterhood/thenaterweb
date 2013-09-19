<?php

include_once GNAT_ROOT.'/lib/core_web.php';

class urlHandler{

	private $url;
	private $controller;
	private $controllerId;


	public function __construct(){

		$this->url = $_GET['url'];
		$this->parseUrl();
		$this->selectController();


	}

	private function parseUrl(){

		$requestUri = explode( '/', $this->url );
		#unset( $requestUri[0] );
		#print_r( $requestUri );

		$requestUri = array_values( $requestUri );

		if ( $requestUri[0] == 'page' || $requestUri[0] == '' ){

	        $this->controllerId = 'mainsite';

            $_GET['id'] = '';

	        if ( count( $requestUri ) > 1 )
                $_GET['id'] = $requestUri[1];

		} else {

	        $_GET['controller'] = array_shift( $requestUri );

	        $sessionMvc = new session( array( 'controller' ) );

	        for( $i = 0; $i < count( $requestUri ); $i+=2 ){
	                $value = "";
	                $key = $requestUri[$i];
	                if ( $i+1 < count( $requestUri ) ){
                        $j = $i + 1;
                        $value = $requestUri[$j];
	 
	                }

	                $_GET[$key] = $value;
	        }

	        $this->controllerId = $sessionMvc->controller;
		}

	}

	private function selectController(){

		$this->controller = "controller/control_".$this->controllerId.".php";

		if ( ! file_exists($this->controller) ){
			$this->controller = "controller/control_error.php";
			$this->controllerId = "Error404";
		}



	}

	public function getController(){
		return $this->controller;
	}

	public function getControllerId(){
		return $this->controllerId;
	}
	

}

?>