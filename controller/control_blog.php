<?php

class controller extends controllerBase{

	private $id;
	private $configFile;

	public function __construct(){

		$configFile = GNAT_ROOT.'/config/section.d/blog.conf.xml';
		$this->readConfig( $this->configFile );


	}


}

?>