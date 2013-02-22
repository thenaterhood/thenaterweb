<?php
/*
* Author: Nate Levesque <public@thenaterhood.com>
* Language: PHP
* Filename: core_config.php
*
* Description:
* 	Contains a class to store configuration options with a getter
* 	so that they can be retrieved arbitrarily.
*/

class config{
	/*
	* Defines a class to hold variables for configuration
	* options.  All variables are accessible only internally
	* to keep things fairly clean.
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
	
	function __construct(){
		/*
		* Sets the configuration options en-masse.
		*/
		
		# Sets the root directory for the main site page, template, and php files
		$this->webcore_root =  '/home/natelev/www/static';
		
		# Sets the directory for storing dynamically created files
		$this->dynamic_directory =  '/home/natelev/www/dynamic';
		
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
		$this->post_directory = '/home/natelev/www/blog/entries';
		
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
			"page_scratchhere.html"
			);
	}
	
	function __get($setting){
		/*
		* Returns the requested config option
		*/
		return $this->$setting;
	}

   
 }
?>
