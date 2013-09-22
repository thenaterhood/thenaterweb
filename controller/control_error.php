<?php
include_once 'controller/interface_controller.php';

class controller extends controllerBase{

	private $id;
	private $configFile;

	public function __construct(){

		
		$configFile = GNAT_ROOT.'/config/section.d/mainsite.conf.xml';
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
