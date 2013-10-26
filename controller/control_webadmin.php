<?php
include_once 'controller/interface_controller.php';
include GNAT_ROOT.'/lib/core_auth.php';

class controller extends controllerBase{

	private $id;
	private $configFile;

	public function __construct(){

		$configFile = GNAT_ROOT.'/config/section.d/webadmin.conf.xml';
		$this->readConfig( $configFile );


	}


}

?>