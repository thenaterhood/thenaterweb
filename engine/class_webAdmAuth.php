<?php

include_once 'class_dataMonger.php';
include_once 'core_web.php';

class webAdmAuth extends dataMonger{


	
	function __construct( $user, $pass, $active ){

		$this->container['user'] = $user;
		$this->container['pass'] = $pass;
		$this->container['active'] = $active;
		$this->container['shadowFile'] = '/var/shadow.json';

		$shadowjson = file_get_contents( $this->container['shadowFile'] );
        $shadow = json_decode( $shadowjson, True );

        if ( $this->container['pass'] == $shadow[$user] || $active ){
            $this->container['active'] = True;
        }


	}

	public function isAuthenticated(){

		return $this->container['active'];

	}

}

?>