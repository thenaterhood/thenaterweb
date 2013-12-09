<?php

include GNAT_ROOT.'/lib/core_feed.php';

class feeds extends controllerBase{

	public function __construct(){

		$this->pageData = array();
		$this->settings = array();

		$this->template = FEEDS_ROOT.'/pages/generate_feed.php';

	}

	public function __call($method, $args){

		$appRoot = 'controller/'.$method;

		if ( file_exists($appRoot.'/main.php') ){
			include $appRoot.'/main.php';
			define( strtoupper($method).'_ROOT', $appRoot );
			
			$blogdef = new $method();

			include $this->template;
		} else {
			echo 'Feed not found.';
		}

	}

}

?>
