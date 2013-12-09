<?php

class auth extends controllerBase{

	public function __construct(){

		$this->settings['template'] = GNAT_ROOT.'/config/template.d/generic_template.php';
		$this->pageData = array();

	}

	public function login(){

		$this->pageData['content'] = pullContent( AUTH_ROOT.'/pages/login' );

		$this->pageData['id'] = 'login';
		$this->pageData['title'] = "NW Authentication";
		$this->pageData['static'] = AUTH_ROOT.'/pages';

		$sessionmgr = SessionMgr::getInstance();

		$this->pageData['csrf_id'] = $sessionmgr->get_csrf_id();
		$this->pageData['csrf_token'] = $sessionmgr->get_csrf_token();

		$pageData = $this->pageData;

		include $this->settings['template'];



	}

	public function logout(){


	}

	private function retrieveUser(){


	}

	private function retrieveUserFromDb(){


	}

	private function retrieveUserFromFile(){


	}


}

?>