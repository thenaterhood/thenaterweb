<?php

class controller extends controllerBase{

	private $id;
	private $configFile;

	public function __construct(){

		$feedSession = new session( array( 'id' ) );

		$configFile = GNAT_ROOT.'/config/section.d/'.$feedSession->id.'.conf.xml';
		$this->readConfig( $configFile );
		$this->settings['template'] = GNAT_ROOT.'/lib/gen_feed.php';


	}




}

?>