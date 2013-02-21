<?php

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
        $this->auto_file_regen = True;
        
        # Whether to save the dynamic files when they are generated
        $this->save_dynamics = True;
        
        # The directory that blog posts are stored in
        $this->post_directory = '/home/natelev/www/blog/entries';
        
        # The default name for visitors who haven't introduced themselves
        $this->default_visitor_name = 'Guest';
        
        # How many posts should be displayed per each blog page
        $this->posts_per_page = 4;
        
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
