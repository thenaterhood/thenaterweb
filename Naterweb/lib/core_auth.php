<?php

include_once NWEB_ROOT.'/lib/core_redirect.php';
include_once NWEB_ROOT.'/lib/builtins/auth/models.php';

use Naterweb\Client\SessionMgr;
use Naterweb\Routing\Urls\UrlBuilder;

function auth_user( $toPage='/', $require_groups=array() ){
    
    
        if ( !is_array($require_groups) ){
            $array = array();
            $array[] = $require_groups;
            $require_groups = $array;
        }

	$sessionmgr = SessionMgr::getInstance();

	if( isset($sessionmgr->valid) && $sessionmgr->valid ){
		$validSession = True;

	} else {
		$sessionmgr->toPage = $toPage;
		$urlBuilder = new UrlBuilder(array('auth'=>'login'));
		$redirect = new Naterweb\Routing\Redirects\Redirect( $toPage, $urlBuilder->build());
		$redirect->apply( 302 );
	}
        
        
        if ( $sessionmgr->uid > -1 ){
            include_once NWEB_ROOT.'/lib/builtins/auth/models.php';
            $dal = new DataAccessLayer();
            $user = $dal->get('nwUser', 'id', $sessionmgr->uid);
            $groups = $user->groups;

            foreach ($require_groups as $g) {
                
                $nwGroup = $dal->get('nwGroup', 'name', $g);
                
                if ( !in_array($nwGroup, $groups) ){
                    $validSession = False;
                }

            }
        
        }

        

	return $validSession;


}

function log_user_in( $user ){

	$sessionmgr = SessionMgr::getInstance();

	$sessionmgr->valid = 1;
	$sessionmgr->user = $user->username;
        $sessionmgr->uid = $user->id;


}

function log_user_out(){

	$sessionmgr = SessionMgr::getInstance();

	unset( $sessionmgr->valid );
	

}

?>
