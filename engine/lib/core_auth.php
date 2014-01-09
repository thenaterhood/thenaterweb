<?php

include_once NWEB_ROOT.'/lib/core_redirect.php';

function auth_user( $toPage='/' ){

	$sessionmgr = SessionMgr::getInstance();

	if( isset($sessionmgr->valid) && $sessionmgr->valid ){
		return true;

	} else {
		$sessionmgr->toPage = $toPage;
		$redirect = new redirect( $toPage, getConfigOption('site_domain').'/?url=auth/login' );
		$redirect->apply( 302 );
	}


	return false;


}

function log_user_in( $user ){

	$sessionmgr = SessionMgr::getInstance();

	$sessionmgr->valid = 1;
	$sessionmgr->user = $user;


}

function log_user_out(){

	$sessionmgr = SessionMgr::getInstance();

	unset( $sessionmgr->valid );
	

}

?>