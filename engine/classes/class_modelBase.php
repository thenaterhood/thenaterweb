<?php
include_once GNAT_ROOT.'/classes/class_model.php';

class ModelBase{

	protected $container;
	private $id;
	public $fields;


	public function save(){


		if ( isset($this->fields) ){
			foreach ($this->fields as $name => $field) {
				$validator = $field->validator;
				print $validator;
				Model::$validator( $field );
			}
			$this->id = DataAccessLayer::save( get_called_class(), $this->as_array() );
		} else {
			throw new Exception('This is not a populated model instance');

		}


	}


	public function delete(){

		if ( isset($this->fields) ){
			DataAccessLayer::delete( get_called_class(), array($this->container) );
		} else {
			throw new Exception('This is not a populated model instance');
		}


	}

	public static function fromArray( $array ){

		$instance = new static();

		foreach ($array as $key => $value) {
			$instance->$key = $value;
		}

		return $instance;
	}


	public function __get( $field ){

		$class = get_called_class();

		if ( array_key_exists($field, $this->fields) ){
			return $this->fields[$field]->data;
		} else if ( $field == 'id' ) {
			return $this->id; 
		} else {
			throw new Exception('Model field "' . $field . '" does not exist.');
		}

	}

	public function __set( $field, $value ){

		$class = get_called_class();

		if ( array_key_exists($field, $this->fields) ){

			$this->fields[$field]->data = $value;

		} else if ( $field == 'id' ) {

			$this->id = $value;

		} else {

			throw new Exception('Model field "' . $field . '" does not exist.');
		}
	}

	public function getFields(){
		return $this->fields;
	}

	public function as_array(){
		$data = array();
		foreach ($this->fields as $name => $value) {
			$data[$name] = $value->data;
		}

		$data['id'] = $this->id;

		return $data;
	}

}