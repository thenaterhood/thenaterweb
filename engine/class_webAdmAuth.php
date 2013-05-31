<?php

include_once 'class_dataMonger.php';
include_once 'core_web.php';

class webAdmAuth extends dataMonger{


	
	function __construct( $user, $pass, $active ){

		$this->container['user'] = $user;
		$this->container['pass'] = $pass;
		$this->container['active'] = $active;
		$this->container['shadowFile'] = '/var/www/shadow.json';

		$shadow = json_decode( file_get_contents( $this->container['shadowFile'] ), True );

		if ( $this->container['pass'] == $shadow[$user] ){
			$this->container['active'] = True;
		}

	}

	public function isAuthenticated(){

		return $this->container['active'];

	}

}

?>