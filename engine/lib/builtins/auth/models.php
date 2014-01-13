<?php

class nwUser extends ModelBase{

	public function __construct(){

		$this->addfield( 'username', 	Model::CharField( array( 'length'=>100) ) );
		$this->addfield( 'first_name', 	Model::CharField( array( 'length'=>100) ) );
		$this->addfield( 'last_name', 	Model::CharField( array( 'length'=>100) ) );
		$this->addfield( 'email', 		Model::CharField( array( 'length'=>100) ) );
		$this->addfield( 'password',	Model::CharField( array( 'length'=>255) ) );
		$this->addfield( 'groups',		Model::ManyToMany( array( 'model'=>'nwGroup', 'related_name'=>'auth_groups') ) );
		$this->addfield( 'active',		Model::BooleanField() );

	}

	public function set_password( $newpass ){
            
                if (function_exists('password_hash')){
                    $this->fields['password']->data = password_hash( $newpass, PASSWORD_DEFAULT );
                } else {
                    $this->fields['password']->data = crypt($newpass);
                }
	}

	public function check_password( $password ){

                if ( function_exists('password_verify')){
                    $hash = password_hash( $password, PASSWORD_DEFAULT );
                    return ( password_verify( $password, $hash ) );

                } else {
                    $hashed_password = $this->fields['password']->data;
                    return (crypt($password, $hashed_password) == $hashed_password);
                    
                }

	}

	public function auth_user( $pass ){
		return ( $this->check_password( $pass ) && $this->fields['active']->data );
	}
	

}

class nwGroup extends ModelBase{

	public function __construct(){

		$this->addfield( 'name',		Model::CharField( array('length'=>50 ) ) );
	}
	

}

?>