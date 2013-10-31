<?php


namespace BlogControl{

	$feedSession = new session( array( 'id' ) );

	include 'controller/'.$feedSession->id.'/main.php';


}

use BlogControl as bc;

class controller extends controllerBase{

	private $id;
	private $configFile;
	private $blogController;

	public function __construct(){


		$this->blogController = new \bc\controller();

		$configFile = $this->blogController->configFile;
		$this->readConfig( $configFile );
		$this->settings['template'] = GNAT_ROOT.'/lib/gen_feed.php';


	}




}

?>