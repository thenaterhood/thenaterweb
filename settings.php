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

/**
 * Defines a class to hold variables for configuration
 * options.  All variables are accessible only internally
 * to keep things fairly clean.
 */
class config{
	
	/**
	 * variables are documented where they are set lower in the code.
	 * @var $container provides a clean way of storing an expanding
	 * mass of variables.
	 */
	private $container;
	
	/**
	 * Sets the configuration options en-masse.
	 */
	public function __construct(){
		# Sets up the empty array
		$this->container = array();

		# Configure the timezone
		date_default_timezone_set('America/New_York');

		# Configure a salt for the passwords. DO NOT share this. 
		# Must be at least 22 characters long
		$this->container['pw_salt'] = 'nweb12345679101112131415';

		# Configure whether or not thenaterweb should 
		# use a database. This takes precedence over the 
		# settings below, so a value of False will ignore the 
		# below database settings and will not use a database
		# even if one is configured.
		$this->container['use_db'] = True;
		
		# Configure the storage backend for the engine. Supported 
		# databases are anything supported by the PDO drivers.
		# Best supported by thenaterweb: sqlite, mysql, pgsql. 
		$this->container['engine_storage_db'] = 'sqlite';
		$this->container['db_user'] = '';
		$this->container['db_password'] = '';
		$this->container['db_host'] = 'localhost';
		$this->container['db_name'] = 'site-data/naterweb_database.db';

		# The database error level, 1 or 0.
		# Set at 1 for development and 0 for production.
		$this->container['db_error_level'] = 1;
		
		# Sets the directory for storing dynamically created files
		$this->container['dynamic_directory'] =  'engine/var/dynamic';
		
		# Whether to save the dynamic files when they are generated.
		# If this is turned off and no dynamic files have been 
		# generated and saved already, regardless of the settings
		# for automatically regenerating files the software will
		# dynamically create the file requested.
		$this->container['save_dynamics'] = True;
		
		# The default name for visitors who haven't introduced themselves
		$this->container['default_visitor_name'] = 'Guest';
		
		# How many posts should be displayed per each blog page
		$this->container['posts_per_page'] = 4;

		# Maximum number of items to show in the feed. If not 
		# using a database, setting this to a high number will 
		# cause performance problems.
		$this->container['max_feed_items'] = 100;
		
		# The domain name of the site (note that this can be automatically
		# determined if needed)
		$this->container['site_domain'] = 'http://localhost:8000';
		
		# The owner/author of the website, used in places where an author
		# is needed, such as the atom feed.
		$this->container['site_author'] = 'Nate Levesque';
		
		# This tells the site software whether to use "friendly" urls
		# rather than dynamic urls, so site.com/page/home rather than
		# site.com/?id=home. ONLY enable this if your server allows you
		# to do url rewriting, otherwise it won't work out well. Requires
		# modifications to .htaccess as currently implemented.
		$this->container['friendly_urls'] = False;

		# Sets which feed format to use when generating feeds. Valid
		# options are 'atom' and 'rss'. Anything other than those will
		# cause the site to default to atom, which is the superior 
		# format.
		$this->container['feed_type'] = 'atom';
		
		# The tracking code that the site should use should be pasted here.
		# Be careful with quotes, as this is read in as a string.  This
		# can also be used to add any other code to the page as well,
		# it is inserted right before the closing </head> tag.
		$this->container['tracking_code'] = "";
		
		# All the files that should be ignored when dynamically looking
		# at directories
		$this->container['hidden_files'] = array(
			".",
			"..",
			"index.php",
			"error_log",
			"post.php",
			"feed.php",
			"tags",
			"posts",
			".htaccess",
			);
			
		# The following are default settings and lengths for variables
		# that would be retrieved via the URL.  DO NOT CHANGE THESE
		# UNLESS YOU ARE ABSOLUTELY SURE WHAT YOU ARE DOING.
		
		# Setting for the maximum length and default value of visitor's name
		$this->container['name'] = array( $this->container['default_visitor_name'], 42 );
		
		# Settings for default and max values of the page id
		$this->container['id'] = array( 'home', 18);
		
		# Easter egg variable settings
		$this->container['konami'] = array( '', 0);
		
		# Node (post) variable settings
		$this->container['node'] = array( '', 30 );
		
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
			return $this->container[$setting];
		else
			return NULL;
	}

   
 }
?>
