<?php

class Model{

	public static function IntegerField( $attrs=array() ){

		if ( array_key_exists('name', $attrs) );
		$instance = (object)$attrs;
		$instance->type = 'Int';
		$instance->validator = 'ValidateIntegerField';

		return $instance;

	}

	public static function ValidateIntegerField( $fieldInst ){

		if ( is_numeric( $fieldInst->data ) ){
			return true;

		} else {

			throw new Exception('Invalid data for type IntegerField.');

		}

	}

	public static function TextField( $attrs=array() ){

		$instance = (object)$attrs;
		$instance->type = 'MediumText';
		$instance->validator = 'ValidateTextField';

		return $instance;
 
	}

	public static function ValidateTextField( $fieldInst ){

		if ( strlen( $fieldInst->data ) > 65535 ){
			throw new Exception('Contents of TextField exceeds length limit.');
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

		if ( strlen($fieldinst->data) > $fieldinst->length ){
			throw new Exception('Length of CharField data exceeds specified length.');
		} else {
			return true;
		}



	}

	public static function ForeignKey( $attrs=array() ){

		if ( ! array_key_exists('model', $attrs) ||
			 ! array_key_exists( 'related_name', $attrs ) ){
			throw new Exception( "ForeignKey relationship requires a model and related name." );
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
			throw new Exception('Invalid model for ForeignKey relationship. 
Expected '.$fieldinst->model.' but got: '. $fieldinst->related_name );
		}
	}

	public static function ManyToMany( $attrs=array() ){

		if ( ! array_key_exists( 'model', $attrs) ||
			 ! array_key_exists( 'related_name', $attrs ) ){
			throw new Exception( "ManyToMany relationship requires a model and related name." );
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
		} else {
			return true;
		}
	}
}

?>