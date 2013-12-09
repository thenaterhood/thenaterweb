<?php

include GNAT_ROOT.'/lib/core_feed.php';

class feeds extends controllerBase{

	public function __construct(){

		$this->pageData = array();
		$this->settings = array();

		$this->template = FEEDS_ROOT.'/pages/generate_feed.php';

	}

	public function __call(){

		$session = new $session( array('id') );
		$appRoot = 'controller/'.$session->id;

		if ( file_exists($appRoot.'/main.php') ){
			include $appRoot.'/main.php';
			$blogdef = new $session->id();

			include $this->template;
		} else {
			echo 'Feed not found.';
		}

	}

?>
