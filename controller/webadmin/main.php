<?php
include GNAT_ROOT.'/lib/core_auth.php';

$admSession = new session( array( 'blogid', 'postid', 'isnew' ) );

class controller extends controllerBase{

	private $id;
	private $configFile;

	public function __construct(){

		$configFile = CONTROLLER_ROOT.'/webadmin.conf.xml';
		$this->readConfig( $configFile );


	}


}

?>