<?php
include_once 'controller/interface_controller.php';

class controller extends controllerBase{

	private $id;
	private $configFile;

	public function __construct(){

		$feedSession = new session( array( 'show' ) );

		$configFile = GNAT_ROOT.'/config/section.d/'.$feedSession->show.'.conf.xml';
		$this->settings['template'] = GNAT_ROOT.'/lib/gen_feed.php';


	}




}

?>