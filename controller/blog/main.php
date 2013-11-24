<?php

class blog extends controllerBase{

	private $id;
	private $configFile;

	public function __construct(){

		$configFile = BLOG_ROOT.'/conf.xml';
		$this->readConfig( $configFile );


	}


}

?>