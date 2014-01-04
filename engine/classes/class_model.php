<?php

class Model{

	public static function IntegerField( $attrs=array() ){

		$instance = (object)$attrs;
		$instance->type = 'Int';
		$instance->validator = 'ValidateIntegerField';

		return $instance;

	}

	public static function ValidateIntegerField( $fieldInst ){

		try{

			int( $fieldInst->data );
			return true;

		} catch (Exception $e) {

			throw new Exception('Invalid data for type IntegerField.');
			return false;

		}

	}

	public static function TextField( $attrs=array() ){

		$instance = (object)$attrs;
		$instance->type = 'MediumText';
		$instance->validator = 'ValidateTextField';

		return $instance;
 
	}

	public static function ValidateTextField( $fieldInst ){

		if ( count($fieldInst->data ) > 65535 ){
			throw new Exception('Contents of TextField exceeds length limit.');
			return false;
		} else {
			return true;
		}

	}

	public static function CharField( $attrs=array() ){

		$instance = (object)$attrs;
		if ( ! array_key_exists('length', $attrs) ){
			throw new Exception('No length given for CharField.');
		} else {
			$instance->type = 'VarChar('.$attrs['length'].')';

		}

		$instance->validator = 'ValidateCharField';
		return $instance;

	}

	public static function ValidateCharField( $fieldinst ){

		if ( count($fieldinst) > $fieldinst->length ){
			throw new Exception('Length of CharField data exceeds specified length.');
			return false;
		} else {
			return true;
		}



	}

	public static function ForeignKey( $attrs=array() ){

		if ( ! array_key_exists('model', $attrs) ||
			 ! array_key_exists( 'related_name', $attrs ) ){
			throw new Exception( "ForeignKey relationship requires a model and related name." );
			return false;
		} else {
			$attrs['validator'] = 'ValidateForeignKey';
			$attrs['type'] = 'foreignkey';

			return (object)$attrs;

		}

	}

	public static function ValidateForeignKey( $fieldinst ){

		if ( property_exists($fieldinst, 'model') && 
			get_class( $fieldinst->fmodel ) == $fieldinst->model ) {
			return true;
		} else {
			throw new Exception('Invalid model for ForeignKey relationship: '. $fieldinst->related_name );
			return false;
		}
	}

	public static function ManyToMany( $attrs=array() ){

		if ( ! array_key_exists( 'model', $attrs) ||
			 ! array_key_exists( 'related_name', $attrs ) ){
			throw new Exception( "ManyToMany relationship requires a model and related name." );
			return false;
		} else {

			$attrs['validator'] = 'ValidateForeignKey';
			$attrs['type'] = 'many2many';
			return (object)$attrs;

		}

	}

	public static function BooleanField( $attrs=array() ){

		$instance = (object)$attrs;
		$instance->type = 'Boolean';
		$instance->validator = "ValidateBooleanField";

		return $instance;


	}

	public static function ValidateBooleanField( $fieldinst ){
		if ( ! is_bool($fieldinst->data) ){
			throw new Exception('Invalid data for BooleanField.');
			return false;
		} else {
			return true;
		}
	}


	public static function ManyToOne( $attrs=array() ){


	}

	public static function DateField( $attrs=array() ){


	}

	public static function PasswordField( $attrs=array() ){


	}


}

?>