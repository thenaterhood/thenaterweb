<?php

include_once NWEB_ROOT.'/lib/core_auth.php';

include_once 'models.php';

use Naterweb\Content\Loaders\ContentFactory;
use Naterweb\Content\Renderers\PhpRenderer;
use Naterweb\Client\SessionMgr;
use Naterweb\Client\request;
use Naterweb\Routing\Urls\UrlBuilder;
use Naterweb\Engine\Configuration;

class auth extends ControllerBase{

	private $dal;

	public function __construct(){

		$this->settings['template'] = \Naterweb\Engine\Configuration::get_option('template');
		// Currently this app requires the database.
		if ( \Naterweb\Engine\Configuration::get_option( 'use_db') || true){
			$this->dal = new DataAccessLayer();
			$this->dal->registerModel( 'nwUser' );
			$this->dal->registerModel( 'nwGroup' );

		}


	}

	public function home(){
		$this->login();
	}

	/**
	 * Logs a user in after validating their credentials, 
	 * or redirects back to the login page.
	 */
	public function login(){

		$sessionmgr = SessionMgr::getInstance();
                $sessionmgr->noRedirect = True;
		$renderer = new PhpRenderer($this->settings['template']);
		$user = null;


		if ( $sessionmgr->check_csrf('post') && 

			$this->check_login( request::sanitized_post('username'), request::post('pass') ) ){
			if (Configuration::get_option('use_db')) {
	                        $user = $this->dal->get('nwUser', 'username', request::sanitized_post('username'));
			}
                        if (is_null($user)){
			    $user = new nwUser();
                            $user->id = -1;
                            $user->username = request::sanitized_post('username');
                            
                        }
                        
			log_user_in( $user );
                        
			$redir_to = $sessionmgr->toPage;
			unset( $sessionmgr->toPage );
			$urlBase = new UrlBuilder(array(request::sanitized_get('controller')=>''));
			$renderer->set_value('urlBase', $urlBase->build());
			$renderer->set_value('content', ContentFactory::loadContentFile( AUTH_ROOT.'/pages/welcome.php' ));
			$renderer->set_value('id', 'login');
			$renderer->set_value('title', 'Naterweb Authentication');
			$renderer->set_value('static', AUTH_ROOT.'/pages');
			$renderer->set_value('to', $redir_to);
                        


		} else {

			$renderer->set_value('content', ContentFactory::loadContentFile( AUTH_ROOT.'/pages/login.php' ));
			$renderer->set_value('id', 'login');
			$renderer->set_value('fail', False);

			$urlBase = new UrlBuilder(array(request::sanitized_get('controller')=>''));
			$renderer->set_value('urlBase', $urlBase->build());
			$renderer->set_value('title', 'Naterweb Authentication');
			$renderer->set_value('static', AUTH_ROOT.'/pages');

		}

		$renderer->render();



	}

	/** 
	 * Renders the group management page where groups can 
	 * be added.
	 */
	public function managegroup(){
            
		$urlBuilder = new UrlBuilder(array('auth'=>'managegroup'));
		if ( auth_user( $urlBuilder->build(), 'nwadmin' ) ){
            
            $sess = SessionMgr::getInstance();
            $sess->noRedirect = True;
	    $renderer = new PhpRenderer($this->settings['template']);

	    $urlBase = new UrlBuilder(array(request::sanitized_get('controller')=>''));
	    $renderer->set_value('urlBase', $urlBase->build());

            $renderer->set_value('groups', $this->dal->getAll( 'nwGroup' ));
	    $renderer->set_value('content', ContentFactory::loadContentFile( AUTH_ROOT.'/pages/managegroup.php'));
	    $renderer->set_value('static', AUTH_ROOT.'/pages');
	    $renderer->set_value('title', 'Manage Groups');

	    $renderer->render();

        } else {

            $this->unauthorized();
        }



	}

	/**
	 * Renders the add group page where a group can be added.
	 */
	public function addgroup(){
		$urlBuilder = new UrlBuilder(array('auth'=>'adduser'));
		if ( ! auth_user( $urlBuilder->build(), 'nwadmin' ) ){
            $this->unauthorized();
        }

		$sessionmgr = SessionMgr::getInstance();
        $sessionmgr->noRedirect = True;

		$renderer = new PhpRenderer($this->settings['template']);

		if ( $sessionmgr->check_csrf('post') && 
			! $this->dal->get('nwGroup', 'name', request::sanitized_post('name') ) ){



			$newgroup = new nwGroup();

			$newgroup->name = request::sanitized_post('name');

			$newgroup->save();
	                $urlBase = new UrlBuilder(array(request::sanitized_get('controller')=>''));
		        $renderer->set_value('urlBase', $urlBase->build());


			$renderer->set_value('content', ContentFactory::loadContentFile( AUTH_ROOT.'/pages/groupadded.php' ));
			$renderer->set_value('id', 'login');
			$renderer->set_value('title', 'Naterweb Authentication');
			$renderer->set_value('static', AUTH_ROOT.'/pages');


		} else {
	   	        $urlBase = new UrlBuilder(array(request::sanitized_get('controller')=>''));
		        $renderer->set_value('urlBase', $urlBase->build());


			$renderer->set_value('content', ContentFactory::loadContentFile( AUTH_ROOT.'/pages/addgroup.php' ));
			$renderer->set_value('static', AUTH_ROOT.'/pages');
			$renderer->set_value('title', 'Add New User');
			$renderer->set_value('id', 'adduser');


		}

		$renderer->render();


	}

	/**
	 * Deletes a user from the database along with 
	 * their group associations.
	 */
	public function deluser(){
		$urlBuilder = new UrlBuilder(array('auth'=>'manage'));
		if ( ! auth_user( $urlBuilder->build(), 'nwadmin' ) ){
                    $this->unauthorized();
                }

		$sessionmgr = SessionMgr::getInstance();
                $sessionmgr->noRedirect = True;

		$user = $this->dal->get( 'nwUser', 'id', request::sanitized_get( 'uid') );

		foreach ($user->getRelated( 'groups' ) as $g) {
			$user->removeRelated( 'groups', $g );
		}

		$user->delete();
		$urlBuilder = new UrlBuilder(array('auth'=>'manage'));
		$redir = new Redirect( 'auth/deluser', $urlBuilder->build());
		$redir->apply(302);




	}

	public function changegroup(){
		print 'not implemented';
	}


	/** 
	 * Updates a user's information and groups
	 */
	public function changeuser(){
		$urlBuilder = new UrlBuilder(array('auth'=>'adduser'));
		if ( ! auth_user( $urlBuilder->build(), 'nwadmin' ) ){
                    $this->unauthorized();
                }

		$sessionmgr = SessionMgr::getInstance();
                $sessionmgr->noRedirect = True;

		$renderer = new PhpRenderer($this->settings['template']);

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

	                $urlBase = new UrlBuilder(array(request::sanitized_get('controller')=>''));
 	                $renderer->set_value('urlBase', $urlBase->build());


			$renderer->set_value('content', ContentFactory::loadContentFile( AUTH_ROOT.'/pages/useradded.php' ));
			$renderer->set_value('id', 'login');
			$renderer->set_value('title', 'Naterweb Authentication');
			$renderer->set_value('static', AUTH_ROOT.'/pages');

		} else {

			$pageData = array();
			$renderer->set_value('content', ContentFactory::loadContentFile( AUTH_ROOT.'/pages/changeuser.php' ));

			$user = $this->dal->get('nwUser', 'id', request::sanitized_get( 'uid') );
			$renderer->set_value('user', $user);
			$renderer->set_value('ingroups', $user->getRelated('groups'));
			$renderer->set_value('groups', $user->dal->getAll('nwGroup'));
			$renderer->set_value('static', AUTH_ROOT.'/pages');
			$renderer->set_value('title', 'Change User');
			$renderer->set_value('id', 'changeuser');
		        $urlBase = new UrlBuilder(array(request::sanitized_get('controller')=>''));
		        $renderer->set_value('urlBase', $urlBase->build());


		}

		$renderer->render();



	}

	/**
	 * Adds a new user to the system
	 */
	public function adduser(){
		$url = new UrlBuilder(array('auth'=>'adduser'));

		if ( ! auth_user( $url->build(), 'nwadmin' ) ){
                    $this->unauthorized();
                }

		$sessionmgr = SessionMgr::getInstance();
                $sessionmgr->noRedirect = True;

		$renderer = new PhpRenderer($this->settings['template']);

		if ( $sessionmgr->check_csrf('post') && 
			 ! $this->dal->get('nwUser', 'username', request::sanitized_post('username') ) ){



			$newuser = new nwUser();

			$newuser->username = request::sanitized_post('username');
			$newuser->set_password( request::post('pass') );

			$newuser->first_name = request::sanitized_post('first_name');
			$newuser->last_name = request::sanitized_post('last_name');
			$newuser->active = True;

			$newuser->save();
		        $urlBase = new UrlBuilder(array(request::sanitized_get('controller')=>''));
		        $renderer->set_value('urlBase', $urlBase->build());


			$renderer->set_value('content', ContentFactory::loadContentFile( AUTH_ROOT.'/pages/useradded.php'));
			$renderer->set_value('id', 'login');
			$renderer->set_value('title', 'Naterweb Authentication');
			$renderer->set_value('static', AUTH_ROOT.'/pages');


		} else {
  		        $urlBase = new UrlBuilder(array(request::sanitized_get('controller')=>''));
		        $renderer->set_value('urlBase', $urlBase->build());


			$renderer->set_value('content', ContentFactory::loadContentFile( AUTH_ROOT.'/pages/adduser.php'));
			$renderer->set_value('static', AUTH_ROOT.'/pages');
			$renderer->set_value('title', 'Add New User');
			$renderer->set_value('id', 'adduser');

		}

		$renderer->render();


	}

	/**
	 * Shows the main user management page
	 */
	public function manage(){
		$url = new UrlBuilder(array('auth'=>'manage'));
		if ( ! auth_user( $url->build(), 'nwadmin' ) ){
                    $this->unauthorized();
                }

		$renderer = new PhpRenderer($this->settings['template']);
		$renderer->set_value('users', $this->dal->getAll( 'nwUser' ));
		$renderer->set_value('content', ContentFactory::loadContentFile( AUTH_ROOT.'/pages/manage.php'));
		$renderer->set_value('static', AUTH_ROOT.'/pages');
		$renderer->set_value('title', 'Manage Users');
		$renderer->set_value('id', 'manage');
		$urlBase = new UrlBuilder(array(request::sanitized_get('controller')=>''));
		$renderer->set_value('urlBase', $urlBase->build());

		$renderer->render();


	}

	/**
	 * Checks a user's credentials with the database 
	 * or a .htaccess file depending on the setup.
	 */
	public function check_login( $user, $plainpasswd ){

		if ( ! \Naterweb\Engine\Configuration::get_option('use_db') ){
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
