<?php
include_once GNAT_ROOT.'/controller/interface_controller.php';

class controller extends controllerBase{

	private $id;
	private $configFile = GNAT_ROOT.'/config/section.d/mainsite.conf.xml';

	public function __construct(){


		$this->readConfig( $this->configFile );


	}
}

?>