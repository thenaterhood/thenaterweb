<?php

class controller extends controllerBase{

	private $id;
	private $configFile;

	public function __construct(){

		$configFile = CONTROLLER_ROOT.'/devrants.conf.xml';
		$this->readConfig( $this->configFile );


	}


}

?>