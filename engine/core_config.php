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
	 * variables are documented where they are set lower in the code
	 * and so documenting here as per phpdoc standards appears excessive.
	 */
	private static $webcore_root;
	private static $dynamic_directory;
	private static $auto_feed_regen;
	private static $auto_file_regen;
	private static $save_dynamics;
	private static $post_directory;
	private static $default_visitor_name;
	private static $hidden_files;
	private static $posts_per_page;
	private static $site_domain;
	private static $site_author;
	private static $tracking_code;
	private static $friendly_urls;
	private static $feed_type;
	
	# Variable settings
	private static $name;
	private static $id;
	private static $konami;
	private static $node;
	private static $track;
	private static $start;
	private static $end;
	
	/**
	 * Sets the configuration options en-masse.
	 */
	function __construct(){
		
		# Sets the root directory for the main site pages and template
		$this->webcore_root =  '/var/www/static';
		
		# Sets the directory for storing dynamically created files
		$this->dynamic_directory =  '/var/www/dynamic';
		
		# Whether or not the blog feed should regenerate automatically
		$this->auto_feed_regen = True;
		
		# Whether or not other dynamic files (such as sitemap) should
		# regenerate automatically
		$this->auto_file_regen = False;
		
		# Whether to save the dynamic files when they are generated.
		# If this is turned off and no dynamic files have been 
		# generated and saved already, regardless of the settings
		# for automatically regenerating files the software will
		# dynamically create the file requested.
		$this->save_dynamics = True;
		
		# The directory that blog posts are stored in
		$this->post_directory = '/var/www/blog/entries';
		
		# The default name for visitors who haven't introduced themselves
		$this->default_visitor_name = 'Guest';
		
		# How many posts should be displayed per each blog page
		$this->posts_per_page = 4;
		
		# The domain name of the site (note that this can be automatically
		# determined if needed)
		$this->site_domain = 'http://www.thenaterhood.com';
		
		# The owner/author of the website, used in places where an author
		# is needed, such as the atom feed.
		$this->site_author = 'Nate Levesque';
		
		# This tells the site software whether to use "friendly" urls
		# rather than dynamic urls, so site.com/page/home rather than
		# site.com/?id=home. ONLY enable this if your server allows you
		# to do url rewriting, otherwise it won't work out well. Requires
		# modifications to .htaccess as currently implemented.
		$this->friendly_urls = True;
		
		# Which feed format to use, atom or rss. The only valid
		# values here are either atom or rss. If the value is
		# not recognized, the site software will default to atom
		# as it is a superior format
		$this->feed_type = 'atom';
		
		# The tracking code that the site should use should be pasted here.
		# Be careful with quotes, as this is read in as a string.  This
		# can also be used to add any other code to the page as well,
		# it is inserted right before the closing </head> tag.
		$this->tracking_code = "<script type='text/javascript'>

		var _gaq = _gaq || [];
		_gaq.push(['_setAccount', 'UA-5020962-1']);
		_gaq.push(['_setDomainName', 'www.thenaterhood.com']);
		_gaq.push(['_trackPageview']);
		
		(function() {
		  var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
		  ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
		  var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
		})();
		
		</script>";
		
		# All the files that should be ignored when dynamically looking
		# at directories
		$this->hidden_files = array(
			".",
			"..",
			"index.php",
			"error_log",
			"post.php",
			"feed.php",
			"feed.xml",
			"inventory.html",
			"tags",
			"posts",
			".htaccess",
			"page_scratchhere.html",
			"page_quack.html",
			"page_rit.html",
			"page_seth.html",
			);
			
		# The following are default settings and lengths for variables
		# that would be retrieved via the URL.  DO NOT CHANGE THESE
		# UNLESS YOU ARE ABSOLUTELY SURE WHAT YOU ARE DOING.
		
		# Setting for the maximum length and default value of visitor's name
		$this->name = array( $this->default_visitor_name, 42 );
		
		# Settings for default and max values of the page id
		$this->id = array( 'home', 18);
		
		# Easter egg variable settings
		$this->konami = array( '', 0);
		
		# Node (post) variable settings
		$this->node = array( '', 10 );
		
		$this->track = array( '', 1 );
		$this->start = array( '0', 5 );
		$this->end = array( "$this->posts_per_page", 5);
		
	}
	
	/**
	 * Returns the value of the requested config key
	 * 
	 * @param $setting - the name of the key
	 * 
	 * @return - the value the key is associated with
	 */
	function __get($setting){

		return $this->$setting;
	}

   
 }
?>
