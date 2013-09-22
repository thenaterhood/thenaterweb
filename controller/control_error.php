<?php
include_once 'controller/interface_controller.php';

class controller extends controllerBase{

	private $id;
	private $configFile;

	public function __construct(){

		
		$configFile = GNAT_ROOT.'/config/section.d/mainsite.conf.xml';
		$this->readConfig( $configFile );

		$errorSession = new session( array( 'type' ) );
		$type = $errorSession->type;

		$this->type();


	}

	private function 404(){

		$this->settings['template'] = GNAT_ROOT.'/lib/pages/hidden_404.php';
		
	}

	private function 403(){

		$this->settings['template'] = GNAT_ROOT.'/lib/pages/hidden_403.php';

	}


}

?>
