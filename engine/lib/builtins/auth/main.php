<?php

include_once NWEB_ROOT.'/lib/core_auth.php';

include_once 'models.php';

class auth extends ControllerBase{

	private $dal;

	public function __construct(){

		$this->settings['template'] = NWEB_ROOT.'/config/template.d/generic_template.php';
		$this->pageData = array();

		if ( getConfigOption( 'use_db') ){
			$this->dal = new DataAccessLayer();
			$this->dal->registerModel( 'nwUser' );
			$this->dal->registerModel( 'nwGroup' );

		}


	}

	/**
	 * Logs a user in after validating their credentials, 
	 * or redirects back to the login page.
	 */
	public function login(){

		$sessionmgr = SessionMgr::getInstance();


		if ( $sessionmgr->check_csrf('post') && 

			$this->check_login( request::sanitized_post('username'), request::post('pass') ) ){

                        $user = $this->dal->get('nwUser', 'username', request::sanitized_post('username'));
                        if (is_null($user)){
                            $user->id = -1;
                            $user->username = request::sanitized_post('username');
                            
                        }
                        
			log_user_in( $user );
                        
			$redir_to = $sessionmgr->toPage;
			unset( $sessionmgr->toPage );

			$this->pageData['content'] = pullContent( AUTH_ROOT.'/pages/welcome' );

			$this->pageData['id'] = 'login';
			$this->pageData['title'] = "Naterweb Authentication";
			$this->pageData['static'] = AUTH_ROOT.'/pages';
			$this->pageData['to'] = $redir_to;

			render_php_template( $this->settings['template'], $this->pageData );


		} else {

			$this->pageData['content'] = pullContent( AUTH_ROOT.'/pages/login' );

			$this->pageData['id'] = 'login';
			$this->pageData['fail'] = False;
			$this->pageData['title'] = "Naterweb Authentication";
			$this->pageData['static'] = AUTH_ROOT.'/pages';

			render_php_template( $this->settings['template'], $this->pageData );

		}



	}

	/** 
	 * Renders the group management page where groups can 
	 * be added.
	 */
	public function managegroup(){

		if ( auth_user( getConfigOption('site_domain').'/?url=auth/managegroup', 'nwadmin' ) ){
                    
                

                    $pageData = array();
                    $pageData['groups'] = $this->dal->getAll( 'nwGroup' );
                    $pageData['content'] = pullContent( AUTH_ROOT.'/pages/managegroup');
                    $pageData['static'] = AUTH_ROOT.'/pages';
                    $pageData['title'] = 'Manage Groups';

                    render_php_template( $this->settings['template'], $pageData );
                } else {
                    $this->unauthorized();
                }


	}

	/**
	 * Renders the add group page where a group can be added.
	 */
	public function addgroup(){

		if ( ! auth_user( getConfigOption('site_domain').'/?url=auth/adduser', 'nwadmin' ) ){
                    $this->unauthorized();
                }

		$sessionmgr = SessionMgr::getInstance();


		if ( $sessionmgr->check_csrf('post') && 
			 ! $this->dal->get('nwGroup', 'name', request::sanitized_post('name') ) ){



			$newgroup = new nwGroup();

			$newgroup->name = request::sanitized_post('name');

			$newgroup->save();


			$this->pageData['content'] = pullContent( AUTH_ROOT.'/pages/groupadded' );

			$this->pageData['id'] = 'login';
			$this->pageData['title'] = "Naterweb Authentication";
			$this->pageData['static'] = AUTH_ROOT.'/pages';

			render_php_template( $this->settings['template'], $this->pageData );


		} else {

			$pageData = array();
			$pageData['content'] = pullContent( AUTH_ROOT.'/pages/addgroup' );
			$pageData['static'] = AUTH_ROOT.'/pages';
			$pageData['title'] = 'Add New User';
			$pageData['id'] = 'adduser';

			render_php_template( $this->settings['template'], $pageData );


		}


	}

	/**
	 * Deletes a user from the database along with 
	 * their group associations.
	 */
	public function deluser(){

		if ( ! auth_user( getConfigOption('site_domain').'/?url=auth/manage', 'nwadmin' ) ){
                    $this->unauthorized();
                }

		$sessionmgr = SessionMgr::getInstance();

		$user = $this->dal->get( 'nwUser', 'id', request::sanitized_get( 'uid') );

		foreach ($user->getRelated( 'groups' ) as $g) {
			$user->removeRelated( 'groups', $g );
		}

		$user->delete();

		$redir = new redirect( 'auth/deluser', '/?url=auth/manage');
		$redir->apply(302);




	}


	/** 
	 * Updates a user's information and groups
	 */
	public function changeuser(){

		if ( ! auth_user( getConfigOption('site_domain').'/?url=auth/adduser', 'nwadmin' ) ){
                    $this->unauthorized();
                }

		$sessionmgr = SessionMgr::getInstance();


		if ( $sessionmgr->check_csrf('post') && 
			  $this->dal->get('nwUser', 'id', request::sanitized_post('uid') ) ){



			$newuser = $this->dal->get( 'nwUser', 'id', request::sanitized_post('uid') );

			$conflicting_unames = $this->dal->get( 'nwUser', 'username', request::sanitized_post('username') );

			if ( ! $conflicting_unames ){
				$newuser->username = request::sanitized_post('username');
			}

			$newuser->first_name = request::sanitized_post('first_name');
			$newuser->last_name = request::sanitized_post('last_name');
			$newuser->active = True;

			$newuser->save();

			$groups = $this->dal->getAll( 'nwGroup' );
			$newGroups = array();

			if(!empty($_POST['groups'])) {
			    foreach($_POST['groups'] as $g) {
			       	$group = $this->dal->get( 'nwGroup', 'name', $g );
			       	$newuser->addRelated( 'groups', $group );
			       	$newGroups[] = $group;
			    }
			}

			foreach ($groups as $g) {
				if ( ! in_array($g, $newGroups ) ){
					$newuser->removeRelated( 'groups', $g );
				}
			}


			$this->pageData['content'] = pullContent( AUTH_ROOT.'/pages/useradded' );

			$this->pageData['id'] = 'login';
			$this->pageData['title'] = "Naterweb Authentication";
			$this->pageData['static'] = AUTH_ROOT.'/pages';

			render_php_template( $this->settings['template'], $this->pageData );


		} else {

			$pageData = array();
			$pageData['content'] = pullContent( AUTH_ROOT.'/pages/changeuser' );
			$user = $this->dal->get('nwUser', 'id', request::sanitized_get( 'uid') );
			$pageData['user'] = $user;
			$pageData['ingroups'] = $user->getRelated( 'groups' );
			$pageData['groups'] = $this->dal->getAll( 'nwGroup' );
			$pageData['static'] = AUTH_ROOT.'/pages';
			$pageData['title'] = 'Change User';
			$pageData['id'] = 'changeuser';

			render_php_template( $this->settings['template'], $pageData );


		}



	}

	/**
	 * Adds a new user to the system
	 */
	public function adduser(){

		if ( ! auth_user( getConfigOption('site_domain').'/?url=auth/adduser', 'nwadmin' ) ){
                    $this->unauthorized();
                }

		$sessionmgr = SessionMgr::getInstance();


		if ( $sessionmgr->check_csrf('post') && 
			 ! $this->dal->get('nwUser', 'username', request::sanitized_post('username') ) ){



			$newuser = new nwUser();

			$newuser->username = request::sanitized_post('username');
			$newuser->set_password( request::post('pass') );

			$newuser->first_name = request::sanitized_post('first_name');
			$newuser->last_name = request::sanitized_post('last_name');
			$newuser->active = True;

			$newuser->save();


			$this->pageData['content'] = pullContent( AUTH_ROOT.'/pages/useradded' );

			$this->pageData['id'] = 'login';
			$this->pageData['title'] = "Naterweb Authentication";
			$this->pageData['static'] = AUTH_ROOT.'/pages';

			render_php_template( $this->settings['template'], $this->pageData );


		} else {

			$pageData = array();
			$pageData['content'] = pullContent( AUTH_ROOT.'/pages/adduser' );
			$pageData['static'] = AUTH_ROOT.'/pages';
			$pageData['title'] = 'Add New User';
			$pageData['id'] = 'adduser';

			render_php_template( $this->settings['template'], $pageData );


		}


	}

	/**
	 * Shows the main user management page
	 */
	public function manage(){

		if ( ! auth_user( getConfigOption('site_domain').'/?url=auth/manage', 'nwadmin' ) ){
                    $this->unauthorized();
                }

		$pageData = array();
		$pageData['users'] = $this->dal->getAll( 'nwUser' );
		$pageData['content'] = pullContent( AUTH_ROOT.'/pages/manage');
		$pageData['static'] = AUTH_ROOT.'/pages';
		$pageData['title'] = 'Manage Users';
		$pageData['id'] = 'manage';

		render_php_template( $this->settings['template'], $pageData );



	}

	/**
	 * Checks a user's credentials with the database 
	 * or a .htaccess file depending on the setup.
	 */
	public function check_login( $user, $plainpasswd ){

		if ( ! getConfigOption('use_db') ){
			return $this->check_htpasswd( $user, $plainpasswd );
		}
		else{
			return $this->check_user_db( $user, $plainpasswd );
		}
	}

	/**
	 * Validates a user from the database
	 */
	private function check_user_db( $username, $plainpasswd ){

		$user = $this->dal->get( 'nwUser', 'username', $username );

		if ( is_null($user) ){
			return $this->check_htpasswd( $username, $plainpasswd );
		}
		else{

			return $user->auth_user( $plainpasswd );
		}
	}

	public function logout(){


	}

	/**
	 * Validates a user from the .htpasswd file
	 */
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
                                $sessionMgr = new SessionMgr();
                                $sessionMgr->htpasswd = True;
				return True;

			} 

		}

		return False;


	}

}

?>