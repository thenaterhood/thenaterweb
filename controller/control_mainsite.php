<?php
include_once GNAT_ROOT.'/controller/interface_controller.php';

class controller extends controllerBase{

	private $id;
	private $configFile;

	public function __construct(){

		$configFile = GNAT_ROOT.'/config/section.d/mainsite.conf.xml';
		$this->readConfig( $this->configFile );


	}


}

?>