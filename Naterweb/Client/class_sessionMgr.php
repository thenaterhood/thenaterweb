<?php

namespace Naterweb\Client;

class SessionMgr{
	
	const SESSION_STARTED = True;
	const SESSION_NOT_STARTED = False;

	private $sessionState = self::SESSION_NOT_STARTED;

	/**
	 * The instance of the session manager
	 */
	private static $instance;


	/**
	 * Returns the instance of the session 
	 * and automatically initializes it if necessary
	 */
	public static function getInstance(){

        if ( !isset(self::$instance)) {
            self::$instance = new self;
        }
       
        self::$instance->startSession();
       
        return self::$instance;
    }

    /**
     * Returns the csrf token id
     */
    public function get_csrf_id() {


        if(isset($_SESSION['token_id'])) { 
                return $_SESSION['token_id'];
        } else {
                $token_id = $this->random(10);

                self::$instance->startSession();

                $_SESSION['token_id'] = $token_id;
                return $token_id;
        }
	}

	/**
	 * Returns the value of the CSRF token
	 */
	public function get_csrf_token() {
        if(isset($_SESSION['token_value'])) {
                return $_SESSION['token_value']; 
        } else {
                $token = hash('sha256', self::$instance->random(500));

                self::$instance->startSession();

                $_SESSION['token_value'] = $token;
                return $token;
        }
 
	}

	private function random($len) {
        if (@is_readable('/dev/urandom')) {
                $f=fopen('/dev/urandom', 'r');
                $urandom=fread($f, $len);
                fclose($f);
        }
 
        $return='';
        for ($i=0;$i<$len;++$i) {
                if (!isset($urandom)) {
                        if ($i%2==0) mt_srand(time()%2147 * 1000000 + (double)microtime() * 1000000);
                        $rand=48+mt_rand()%64;
                } else $rand=48+ord($urandom[$i])%64;
 
                if ($rand>57)
                        $rand+=7;
                if ($rand>90)
                        $rand+=6;
 
                if ($rand==123) $rand=52;
                if ($rand==124) $rand=53;
                $return.=chr($rand);
        }
        return $return;
	}

	/**
	 * Checks the validity of the CSRF token 
	 * from the given method (post or get)
	 */
	public function check_csrf($method) {
        if( strtolower($method) == 'post' || strtolower($method) == 'get') {
            $post = $_POST;
            $get = $_GET;
            if(isset(${$method}[self::$instance->get_csrf_id()]) && 
            	(${$method}[self::$instance->get_csrf_id()] == self::$instance->get_csrf_token())) {
                    return true;
            } else {
                    return false;        
            }
        } else {
                return false;        
        }
	}

	/**
	 * Generates csrf-safe form names for a form.
	 */
	public function form_names($names, $regenerate) {
 
        $values = array();
        foreach ($names as $n) {
                if($regenerate == true) {
                        unset($_SESSION[$n]);
                }
                $s = isset($_SESSION[$n]) ? $_SESSION[$n] : $this->random(10);
                $_SESSION[$n] = $s;
                $values[$n] = $s;        
        }
        return $values;
	}

    /**
     * Starts or restarts the session
     */
    public function startSession(){
        if ( $this->sessionState == self::SESSION_NOT_STARTED ) {
            $this->sessionState = session_start();
        }
       
        return $this->sessionState;
    }

    /**
     * Allows for storing data into the session
     */
    public function __set( $name , $value ){

    	if ( $this->sessionState == self::SESSION_NOT_STARTED ){
    		self::$instance->startSession();
    	}

        $_SESSION[$name] = $value;
    }

    /**
     * Retrieves data from the session
     */
    public function __get( $name ){

        if ( isset($_SESSION[$name])) {
            return $_SESSION[$name];
        }
    }

    public function __isset( $name ){
        return isset($_SESSION[$name]);
    }
   
   
    public function __unset( $name ){
        unset( $_SESSION[$name] );
    }

    public function destroy(){
        if ( $this->sessionState == self::SESSION_STARTED )
        {
            $this->sessionState = !session_destroy();
            unset( $_SESSION );
           
            return !$this->sessionState;
        }
       
        return FALSE;
    }
}

?>
