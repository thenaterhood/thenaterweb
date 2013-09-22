<?php

include_once GNAT_ROOT.'/lib/core_blog.php';

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

		$_GET['id'] = '';
		$_GET['controller'] = 'page';

		$requestUri = array_values( $requestUri );


	    if ( count( $requestUri ) >= 1 )
            $_GET['controller'] = array_shift( $requestUri );

        if ( count( $requestUri ) >= 2 )

	        $_GET['id'] = array_shift( $requestUri );

	    if ( count( $requestUri ) > 2 ){

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