<?php

class controller extends controllerBase{

	private $id;
	private $configFile;

	public function __construct(){

		$configFile = CONTROLLER_ROOT.'/blog.conf.xml';
		$this->readConfig( $configFile );


	}


}

?>