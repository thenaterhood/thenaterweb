<?php 

/**
 * An empty test model so that we can fool anything
 * using get_class to validate type.
 */
class testModel{

	public function __construct(){

	}

}

class modelTest extends PHPUnit_Framework_TestCase {

	protected function setUp(){


	}

	protected function tearDown(){



	}

	public function test_integerfield_valid(){

		$field = Model::IntegerField( array('name'=>'test') );

		$this->assertTrue( is_object($field) );

		$this->assertEquals( $field->type, 'Int' );
		$this->assertEquals( $field->validator, 'ValidateIntegerField' );
		$this->assertEquals( $field->name, 'test' );

	}

	public function test_validate_integerfield_valid(){

		$field = Model::IntegerField( array('name'=>'test') );

		$field->data = 42;

		$this->assertTrue( Model::{$field->validator}( $field ) );
	}	

	/**
	 * @expectedException Exception
	 * @expectedExceptionMessage Invalid data for type IntegerField.
	 */
	public function test_validate_integerfield_invalid(){

		$field = Model::IntegerField( array('name'=>'test') );

		$field->data = 'foobar';

		Model::{$field->validator}( $field );
	}

	public function test_textfield_valid(){

		$field = Model::TextField( array('name'=>'test') );

		$this->assertEquals( $field->type, 'MediumText' );
		$this->assertEquals( $field->validator, 'ValidateTextField' );
		$this->assertEquals( $field->name, 'test' );

	}	

	public function test_validate_textfield_valid(){

		$field = Model::TextField( array('name'=>'test') );

		$field->data = 'foobar';

		$this->assertTrue( Model::{$field->validator}( $field ) );
	}

	/**
	 * @expectedException Exception
	 * @expectedExceptionMessage Contents of TextField exceeds length limit.
	 */
	public function test_validate_textfield_too_long(){

		$field = Model::TextField( array('name'=>'test') );

		$field->data = str_repeat('s', 65536 );

		Model::{$field->validator}( $field );
	}	

	public function test_charfield_valid(){

		$field = Model::CharField( array('name'=>'test', 'length'=>20 ) );

		$this->assertEquals( $field->type, 'VarChar(20)' );
		$this->assertEquals( $field->validator, 'ValidateCharField' );
		$this->assertEquals( $field->name, 'test' );

	}	

	/**
	 * @expectedException Exception
	 * @expectedExceptionMessage No length given for CharField.
	 */
	public function test_charfield_invalid(){

		$field = Model::CharField( array('name'=>'test' ) );


	}	

	public function test_validate_charfield_valid(){

		$field = Model::TextField( array('name'=>'test', 'length'=>20) );

		$field->data = 'foobar';

		$this->assertTrue( Model::{$field->validator}( $field ) );
	}

	/**
	 * @expectedException Exception
	 * @expectedExceptionMessage Length of CharField data exceeds specified length.
	 */
	public function test_validate_charfield_too_long(){

		$field = Model::CharField( array('name'=>'test', 'length'=>2 ) );

		$field->data = 'foobar';

		Model::{$field->validator}( $field );
	}	

	public function test_foreignkey_valid(){

		$field = Model::ForeignKey( array('related_name'=>'test', 'model'=>'testModel') );

		$this->assertEquals( $field->type, 'foreignkey' );
		$this->assertEquals( $field->validator, 'ValidateForeignKey' );
	}

	/**
	 * @expectedException Exception
	 * @expectedExceptionMessage ForeignKey relationship requires a model and related name.
	 */
	public function test_foreignkey_invalid(){

		$field = Model::ForeignKey( array( 'model'=>'TestClass') );

	}

	public function test_foreignkey_data_valid(){

		$field = Model::ForeignKey( array('related_name'=>'testModel', 'model'=>'testModel') );

		$field->fmodel = new testModel();

		$this->assertTrue( Model::{$field->validator}( $field ) );

	}

	/**
	 * @expectedException Exception
	 * @expectedExceptionMessage Invalid model for ForeignKey relationship.
	 */
	public function test_foreignkey_data_invalid(){

		$field = Model::ForeignKey( array('related_name'=>'testModel', 'model'=>'WrongModel') );

		$field->fmodel = new testModel();

		Model::{$field->validator}( $field );

	}

	public function test_m2m_valid(){

		$field = Model::ManyToMany( array('related_name'=>'test', 'model'=>'testModel') );

		$this->assertEquals( $field->type, 'many2many' );
		$this->assertEquals( $field->validator, 'ValidateForeignKey' );
	}

	/**
	 * @expectedException Exception
	 * @expectedExceptionMessage ManyToMany relationship requires a model and related name.
	 */
	public function test_m2m_invalid(){

		$field = Model::ManyToMany( array( 'model'=>'TestClass') );

	}

	public function test_booleanfield_valid(){

		$field = Model::BooleanField();

		$this->assertEquals( $field->type, 'Boolean' );
		$this->assertEquals( $field->validator, 'ValidateBooleanField' );
	}

	public function test_booleanfield_data_valid(){

		$field = Model::BooleanField();

		$field->data = True;

		$this->assertTrue( Model::{$field->validator}( $field ) );

	}

	/**
	 * @expectedException Exception
	 * @expectedExceptionMessage Invalid data for BooleanField.
	 */
	public function test_booleanfield_data_invalid(){

		$field = Model::BooleanField();

		$field->data = 'notbool';

		Model::{$field->validator}( $field );

	}

}

?>