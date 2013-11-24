<?php

class error extends controllerBase{

	private $id;
	private $configFile;

	public function __construct(){

		
		$configFile = CONTROLLER_ROOT.'/error.conf.xml';
		$this->readConfig( $configFile );

		$errorSession = new session( array( 'type' ) );
		$type = "err404";
		if ( $errorSession->type != '' )
			$type = "err".$errorSession->type;
		
		$this->$type();


	}

	private function err404(){
		$_GET['id'] = '404';
		header('HTTP/1.0 404 Not Found');
		
	}

	private function err403(){
		$_GET['id'] = '403';
		header('HTTP/1.0 403 Forbidden');

	}


}

?>
