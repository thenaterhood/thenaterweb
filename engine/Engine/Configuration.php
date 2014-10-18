<?php
/**
 * Contains configuration settings for the site engine to use
 * as a php class that can be directly accessed.
 * 
 * @author Nate Levesque <public@thenaterhood.com>
 * Language: PHP
 * Filename: core_config.php
 *
 */
namespace Naterweb\Engine;
/**
 * Defines a class to hold variables for configuration
 * options.  All variables are accessible only internally
 * to keep things fairly clean.
 */
class Configuration{
	
	/**
	 * variables are documented where they are set lower in the code.
	 * @var $container provides a clean way of storing an expanding
	 * mass of variables.
	 */
	private $container;

	private static $instance;
	
	/**
	 * Sets the configuration options en-masse.
	 */
	private function __construct(){
		# Sets up the empty array
		$this->container = parse_ini_file(NWEB_ROOT.'/../settings.ini.php');

		# Configure the timezone
		date_default_timezone_set($this->container['timezone']);


		
		# Node (post) variable settings
		$this->container['node'] = array( '', 30 );
		$this->container['id'] = array('home', 18);
		
		$this->container['track'] = array( '', 1 );
		$this->container['start'] = array( '0', 5 );
		$this->container['end'] = array( "$this->posts_per_page", 5);
		
	}
	
	/**
	 * Returns the value of the requested config key
	 * 
	 * @param $setting - the name of the key
	 * 
	 * @return - the value the key is associated with
	 */
	public function __get($setting){

		if ( array_key_exists($setting, $this->container) )
			return str_replace('NWEB_ROOT', NWEB_ROOT, $this->container[$setting]);

		else
			return NULL;
	}


	public static function get_option($setting){
		if ( ! self::$instance ){
			self::$instance = new self();
		}

		return self::$instance->$setting;
  	}

 }
