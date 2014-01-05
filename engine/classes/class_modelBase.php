<?php
include_once GNAT_ROOT.'/classes/class_model.php';

class ModelBase{

	protected $container;
	private $dal;
	private $id;
	public $fields = array();
	private $relatives = array();


	/**
	 * Saves a model via the data access layer.
	 * This function validates the fields of the model 
	 * according to the type they are specified to be, throwing 
	 * exceptions if they do not validate. It is recommended that 
	 * the model be registered with the data access layer before 
	 * attempting to store models using the layer.
	 *
	 * @throws - throws an exception if the model does not contain data
	 */
	public function save(){


		if ( isset($this->fields) ){
			foreach ($this->fields as $name => $field) {
				$validator = $field->validator;
				Model::$validator( $field );
			}
			$this->id = DataAccessLayer::save( get_called_class(), $this->as_array() );
		} else {
			throw new Exception('This is not a populated model instance');

		}


	}

	/**
	 * Adds a field to the model. This is intended to be called 
	 * in the constructor of the classes that inherit this class 
	 * in order to register the fields and relational functionality 
	 * with the class and the data access layer. Adding fields 
	 * dynamically is not supported, so adding columns to the database 
	 * after the model has been registered with the data access layer 
	 * the first time needs to be done manually.
	 *
	 * @param $name - the name of the field to add
	 * @param $object - an instance of a Model field.
	 *
	 * @throws Exception - throws an exception for unsupported field types
	 */
	protected function addfield( $name, $object ){
		if ( $object->type != 'foreignkey' && $object->type != 'many2many' ){

			$this->fields[$name] = $object;

		} else if ( $object->type == 'foreignkey' || $object->type == "many2many" ){

			$this->relatives[$name] = $object;
			$this->setupRelated( $name );

		} else {
			throw new Exception( "Unrecognized or unsupported field type: " . $object->type );
		}
	}


	/**
	 * Deletes a model instance from the database via the 
	 * data access layer. It is recommended that the model 
	 * be registered with the data access layer beforehand 
	 * in order to make sure the necessary database structures 
	 * exist.
	 *
	 * @throws Exception - throws an exception if the model 
	 * 	does not contain fields or data.
	 */
	public function delete(){

		if ( isset($this->fields) ){
			DataAccessLayer::delete( get_called_class(), $this->as_array() );
		} else {
			throw new Exception('This is not a populated model instance');
		}


	}

	/**
	 * Creates a new instance of the model and populates it 
	 * from an array. The array must contain ONLY the data 
	 * that the model is expected to contain, or the model 
	 * instance will throw exceptions. This method is used 
	 * by the data access layer to create model instances from 
	 * an associative array returned by the database layer.
	 *
	 * @param $array - an associative array (string=>string) 
	 * 	of data for the model. This array does not contain 
	 * 	fields or types, as the model is initialized first 
	 * 	and those are set in the model's own constructor.
	 *
	 * @return $instance - a populated model instance
	 */
	public static function fromArray( $array ){

		$instance = new static();

		foreach ($array as $key => $value) {
			$instance->$key = $value;
		}

		return $instance;
	}

	/**
	 * Creates a new instance of the model from an 
	 * stdClass instance. The instance must contain 
	 * ONLY the fields that the model itself contains 
	 * or exceptions will be thrown. Field types do not 
	 * need to be defined as those will be configured in 
	 * the model's constructor prior to populating the data.
	 *
	 * @param $class - an stdClass instance containing model data
	 *
	 * @return - an instance of the model class
	 */
	public static function fromStdClass( $class ){
		return self::fromArray( (array)$class );
	}


	/**
	 * Retrieves data from the model and returns it. This is necessary 
	 * as data is contained in the field instances in the model and isn't 
	 * directly available. Returns the data or the id of the object.
	 *
	 * @param $field - the field of the model to return data for
	 *
	 * @return - the data contained in the field
	 *
	 * @throws Exception - throws an exception if the model field does not exist.
	 */
	public function __get( $field ){

		$class = get_called_class();

		if ( array_key_exists($field, $this->fields) ){
			return $this->fields[$field]->data;
		} else if ( $field == 'id' ) {

			return $this->id; 

		} else if ( array_key_exists($field, $this->relatives) ){

			return $this->getRelated( $field );

		} else {
			throw new Exception('Model field "' . $field . '" does not exist.');
		}

	}

	/**
	 * Sets up any foreignkey and manyToMany relationship fields in the database 
	 * as the fields are registered with the model. These fields are handled as 
	 * separate models contained by the main model, which is why registering 
	 * all of the model fields with the model class is required. This method 
	 * is automatically called when any new ForeignKey or ManyToMany field is 
	 * registered for the first time (adding these types of fields after the 
	 * initial registration with the data access layer is supported), as well 
	 * as when any such field is accessed or changed in order to ensure that 
	 * the necessary structures are in place to handle them.
	 *
	 * @param $name - the name of the relationship which matches the name of 
	 * 	the field in the model.
	 *
	 * @return array - an array containing the data access layer with the 
	 *	models for the fields registered, and the related name (which 
	 *	corresponds to the internal name of the model and the database table).
	 */
	private function setupRelated( $name ){

		$model = $this->relatives[$name];
		$dal = new DataAccessLayer();

		$relatedmodel = (object)array();
		$relatedmodel->fields = array();
		$relatedmodel->fields[ get_called_class() ] = Model::IntegerField();
		$relative = $this->relatives[ $name ];
		$relatedmodel->fields[ $relative->related_name ] = Model::IntegerField();

		if ( $model->type == 'foreignkey' ){


			$relatedmodel->name = get_called_class() . '_fk_' . $name;

			$dal->registerModelFromInstance( $relatedmodel );


		} else if ( $model->type == 'many2many' ){

			$relatedmodel->name = get_called_class() . '_m2m_' . $name;

			$dal->registerModelFromInstance( $relatedmodel );


		}

		return array( 'dal'=>$dal, 'rel_name'=>$relatedmodel->name );


	}

	/**
	 * Converts objects retrieved from the database for a relational field 
	 * into the proper type. Due to the internal workings of the modelBase, 
	 * and to avoid duplicate database data, these are retrieved from the 
	 * data access layer as stdClass instances which are then converted to 
	 * the proper type using this method. Iterates through the stdClass methods 
	 * to retrieve the row ids of the objects then retrieves them.
	 *
	 * @param $objects - an array of stdClass objects retrieved from the 
	 * 	data access layer
	 * @param $rel_name - the name of the relationship (relation_name in the field
	 *	attributes) which is used to locate the right column
	 * @param $model - the model that is being related to and that the objects 
	 *	will be translated to.
	 * @param $dal - the data access layer with all the necessary models 
	 * 	registered.
	 *
	 * @return $related - an array of model instances
	 */
	private function convertRelatedToModel( $objects, $rel_name, $model, $dal ){



		if ( ! is_array($objects ) ){
			$array = array();
			$array[] = $objects;
			$objects = $array;
		}

		$related = array();

		foreach ($objects as $value) {


			$instance = $dal->get( $model, 'id', $value->$rel_name );

			$related[] = $instance;
			

		}

		return $related;
	}

	/**
	 * Gets the objects that are associated with the model in a 
	 * relational way by ForeignKey or ManyToMany fields and returns them.
	 * The field must have already been added to the model via the addfield 
	 * method.
	 *
	 * @param $name - the field name from the main model to retrieve the related 
	 * 	objects for.
	 *
	 * @return - an array of the related objects
	 */
	public function getRelated( $name ){

		$related_props = $this->setupRelated( $name );
		$dal = $related_props['dal'];
		$relname = $related_props['rel_name'];

		$field = $this->relatives[$name];

		if ( $field->type == 'foreignkey' ){

			$relations = $dal->get( $relname, get_called_class(), $this->id, True ); 

			return $this->convertRelatedToModel( $relations, $field->related_name, $field->model, $dal );

		} else if ( $field->type == 'many2many' ){

			$relations = $dal->filter( $relname, array( get_called_class()=>$this->id ), True );

			return $this->convertRelatedToModel( $relations, $field->related_name, $field->model, $dal );

		}


	}

	/**
	 * Replaces a single foreignkey relationship
	 * value and raises an exception if the relationship 
	 * is not a foreignkey. The field must have already 
	 * been added to the model by way of the addfield method.
	 *
	 * @param $name - the name of the modelField to set 
	 *	a relation for.
	 * @param $value - the new instance of the model to 
	 * 	set a new relation towards.
	 *
	 * @throws Exception - throws an exception of the field to be 
	 * 	set is not a ForeignKey relationship.
	 */
	public function setRelated( $name, $value ){

		$related_props = $this->setupRelated( $name );
		$dal = $related_props['dal'];
		$relname = $related_props['rel_name'];

		$model = $this->relatives[$name];

		if ( $model->type == 'foreignkey' ){

			$fk_relative = $dal->get( $relname, get_called_class(), $this->id, True );

			$relative = $this->relatives[ $name ];

			$fk_relative->{$relative->related_name} = $value->id;

			$dal->save( $relname, (array)$fk_relative );


		} else {

			throw new Exception( 'Relationship value can only be set for ForeignKey relationships. 
				Use the addRelated and removeRelated functions for other types of relationships.');

		}

	}

	/** 
	 * Adds another item to the object's ManyToMany relationship 
	 * with another table. This method updates the join table 
	 * with the additional data if the object is not already 
	 * associated with the model. The field is required to have been 
	 * added to the model by way of the addfield method.
	 *
	 * @param $name - the name of the model field to 
	 * 	add a relating item to.
	 * @param $value - the item to add to the related array.
	 *
	 * @throws Exception - throws an exception when trying to add objects 
	 * 	to a ForeignKey relationship rather than a ManyToMany relationship.
	 */
	public function addRelated( $name, $value ){

		$related_props = $this->setupRelated( $name );
		$dal = $related_props['dal'];
		$relname = $related_props['rel_name'];

		$model = $this->relatives[$name];

		if ( $model->type == 'many2many' ){

			$relative_name = $this->relatives[$name]->related_name;
			$filter_criteria = array();
			$filter_criteria[$relative_name] = $value->id;
			$filter_criteria[get_called_class()] = $this->id;

			$relatives = $dal->filter( $relname, $filter_criteria, True );

			if ( count($relatives) < 1 ){
				$new_m2m_relation = new stdClass();
				$new_m2m_relation->{get_called_class() } = $this->id;

				$relative = $this->relatives[ $name ];
				$new_m2m_relation->{$relative->related_name} = $value->id;

				$dal->save( $relname, (array)$new_m2m_relation );
			}


		} else {

			throw new Exception( 'Relationship values can only be added for ManyToMany relationships. 
				Use the setRelated function for ForeignKey relationships.');

		}


	}

	/**
	 * Removes a related object from the model ManyToMany field.
	 * It is required that the field has been added to the model via the 
	 * addfield method prior to using this method.
	 *
	 * @param $name - the name of the model field to remove the relative from.
	 * @param $value - the initialized model to remove from the relatives.
	 *
	 * @throws Exception - throws an exception when trying to remove an object 
	 * 	from something other than a ManyToMany field.
	 */
	public function removeRelated( $name, $value ){

		$related_props = $this->setupRelated( $name );
		$dal = $related_props['dal'];
		$relname = $related_props['rel_name'];

		$model = $this->relatives[$name];

		if ( $model->type == 'many2many' ){

			$relative_name = $this->relatives[$name]->related_name;

			$filter_criteria = array();
			$filter_criteria[$relative_name] = $value->id;
			$filter_criteria[get_called_class()] = $this->id;

			$relatives = $dal->filter( $relname, $filter_criteria, True );

			
			if ( count($relatives) > 0 ){

				$relative = $this->relatives[ $name ];

				$dal->delete( $relname, (array)$relatives[0] );

			}


		} else {

			throw new Exception( 'Relationship values can only be removed from ManyToMany relationships. 
				Use the setRelated function for ForeignKey relationships.');

		}


	}

	/**
	 * Sets the data for a field in the model. This is required 
	 * as data is stored internally in instances of fields rather 
	 * than directly in a container or as properties.
	 *
	 * @param $field - the field name to set data for
	 * @param $value - the data to set
	 * 
	 * @throws Exception - throws an exception if the model field 
	 * 	does not exist in the model.
	 */
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

	/**
	 * Returns the model's fields array which contains the field names, 
	 * types, and contents. This is used by the data access layer 
	 * to register the model.
	 */
	public function getFields(){
		return $this->fields;
	}

	/** 
	 * Returns the contained data of the object as an associative 
	 * array stripped of the field types. This is used when saving 
	 * and deleting an object, as the field types have been validated 
	 * by the class already and the types are already configured 
	 * in the database.
	 */
	public function as_array(){
		
		$data = array();
		foreach ($this->fields as $name => $value) {
			$data[$name] = $value->data;
		}

		$data['id'] = $this->id;

		return $data;
	}

}