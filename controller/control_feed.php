<?php
include_once 'controller/interface_controller.php';

class controller extends controllerBase{

	private $id;
	private $configFile;

	public function __construct(){

		$this->settings['template'] = GNAT_ROOT.'/lib/gen_feed.php';


	}




}

?>