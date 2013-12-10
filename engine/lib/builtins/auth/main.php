<?php

include_once GNAT_ROOT.'/lib/core_auth.php';
include_once GNAT_ROOT.'/lib/core_redirect.php';

class auth extends controllerBase{

	public function __construct(){

		$this->settings['template'] = GNAT_ROOT.'/config/template.d/generic_template.php';
		$this->pageData = array();

	}

	public function login(){

		$sessionmgr = SessionMgr::getInstance();


		if ( $sessionmgr->check_csrf('post') && 

			$this->check_htpasswd( request::sanitized_post('username'), request::post('pass') ) ){

			log_user_in( request::sanitized_post('username') );

			$redir_to = $sessionmgr->toPage;
			unset( $sessionmgr->toPage );

			$this->pageData['content'] = pullContent( AUTH_ROOT.'/pages/welcome' );

			$this->pageData['id'] = 'login';
			$this->pageData['title'] = "Naterweb Authentication";
			$this->pageData['static'] = AUTH_ROOT.'/pages';
			$this->pageData['to'] = $redir_to;

			$pageData = $this->pageData;

			include $this->settings['template'];


		} else {

			$this->pageData['content'] = pullContent( AUTH_ROOT.'/pages/login' );

			$this->pageData['id'] = 'login';
			$this->pageData['title'] = "Naterweb Authentication";
			$this->pageData['static'] = AUTH_ROOT.'/pages';


			$this->pageData['csrf_id'] = $sessionmgr->get_csrf_id();
			$this->pageData['csrf_token'] = $sessionmgr->get_csrf_token();

			$pageData = $this->pageData;

			include $this->settings['template'];

		}



	}

	public function logout(){


	}

	private function check_htpasswd($user, $plainpasswd){

		# Log the user in

		if( !($passwd = @fopen( "./.htpasswd", "r" ))) {  
			 
			  return False;
		} 

		while( $pwent = fgets( $passwd, 100 )) {

			$part = explode( ":", chop($pwent));
			$pass = explode( "\$", $part[1]);

			$salt=$pass[2];
			$len = strlen($plainpasswd);
			$text = $plainpasswd.'$apr1$'.$salt;
			$bin = pack("H32", md5($plainpasswd.$salt.$plainpasswd));
			for($i = $len; $i > 0; $i -= 16) { 
		        $text .= substr($bin, 0, min(16, $i)); 
		    }
			for($i = $len; $i > 0; $i >>= 1) { 
		        $text .= ($i & 1) ? chr(0) : $plainpasswd{0}; 
		    }
			$bin = pack("H32", md5($text));
			for($i = 0; $i < 1000; $i++) {
				 $new = ($i & 1) ? $plainpasswd : $bin;
				 if ($i % 3) $new .= $salt;
				 if ($i % 7) $new .= $plainpasswd;
				 $new .= ($i & 1) ? $bin : $plainpasswd;
				 $bin = pack("H32", md5($new));
			}
			$tmp="";
			for ($i = 0; $i < 5; $i++) {
				 $k = $i + 6;
				 $j = $i + 12;
				 if ($j == 16) $j = 5;
				 $tmp = $bin[$i].$bin[$k].$bin[$j].$tmp;
			}
			$tmp = chr(0).chr(0).$bin[11].$tmp;
			$tmp = strtr(strrev(substr(base64_encode($tmp), 2)),
			   "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/",
			   "./0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz");
			$hashedpasswd = "$"."apr1"."$".$salt."$".$tmp;

			if (($user == $part[0]) && ($hashedpasswd == $part[1])){

				return True;

			} 

		}

		return False;


	}

	private function retrieveUser(){


	}

	private function retrieveUserFromDb(){


	}

	private function retrieveUserFromFile(){


	}


}

?>