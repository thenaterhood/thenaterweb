<?php

class page extends controllerBase{

	private $id;
	private $configFile;

	public function __construct(){

		$configFile = PAGE_ROOT.'/mainsite.conf.xml';
		$this->readConfig( $configFile );


	}


}

?>