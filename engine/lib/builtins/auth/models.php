<?php

class nwUser extends modelBase{

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
		$this->fields['password']->data = password_hash( $newpass, PASSWORD_DEFAULT );
	}

	public function check_password( $password ){

		$hash = password_hash( $password, PASSWORD_DEFAULT );

		return ( password_verify( $password, $hash ) );
	}

	public function auth_user( $pass ){
		return ( $this->check_password( $pass ) && $this->fields['active']->data );
	}
	

}

class nwGroup extends modelBase{

	public function __construct(){

		$this->addfield( 'name',		Model::CharField( array('length'=>50 ) ) );
	}
	

}

?>