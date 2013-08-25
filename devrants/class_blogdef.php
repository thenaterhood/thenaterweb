<?php

/**
 *
 * Defines the blog that is stored in the current directory.
 * This is a configuration file for the contained blog, that all
 * of the blog scripts pull from. It provides a unified place for
 * configuring the data needed for the blog platform.
 *
 */

/**
 * Defines a class that contains the blog data
 * @author Nate Levesque <public@thenaterhood.com>
 * @since 05/31/2013
 */
class blogdef{
	
	/**
	 * @var $container - provides a container to store the data
	 */
	private $container;

	/**
	 * Constructs an instance of the class. All blog data is defined in this
	 * section. Due to the fact that the container is a map, adding information
	 * is straightforward.
	 */
	function __construct(){

		# Sets up an array to store data
		$this->container = array();

		# Configure the path to where blog posts or articles
		# are stored for this blog. This can be an absolute path
		# or a relative path. If the post directory is in the same
		# directory as the blog, this can be relative - i.e, just the name.
		# If it's elsewhere, the full path is necessary.
		$this->container['post_directory'] = "/home/natelev/www/devrants/entries";

		# Configure the identity of the blog for use when generating
		# urls to the blog and saving files. This is publicly visible.
		$this->container['id'] = "devrants";

		# Configure the title of the blog. This is publically visible
		# and is used to brand the feed and blog pages.
		$this->container['title'] = "Developer Rants";

		# Set a catchline for the blog. This is publically visible
		# and is used in the feed.
		$this->container['catchline'] = "Problems, discoveries, and other development fun";

		# Set the location of the feed. If you are using the builtin
		# feed provided by gnat, then enter http://thisdomain.com/yourblog/feed.php
		# If you have burned your feed or are using another provider, give
		# them the above url and enter the url of the feed that your
		# provider gives you here.
		$this->container['feedurl'] = "http://www.thenaterhood.com/devrants/feed.php";

		# Any comment code for the post can be placed here, such as Disqus.
		# Homebrew can also be placed here, though to simplify things, you
		# would likely want to create an api for your homebrew code. This is
		# appended at the end of each post page.
		$this->container['commentCode'] = "    <div id='disqus_thread'></div>
    <script type='text/javascript'>
        /* * * CONFIGURATION VARIABLES: EDIT BEFORE PASTING INTO YOUR WEBPAGE * * */
        var disqus_shortname = 'devrants'; // required: replace example with your forum shortname

        /* * * DON'T EDIT BELOW THIS LINE * * */
        (function() {
            var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
            dsq.src = '//' + disqus_shortname + '.disqus.com/embed.js';
            (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
        })();
    </script>
    <noscript>Please enable JavaScript to view the <a href='http://disqus.com/?ref_noscript'>comments powered by Disqus.</a></noscript>
    <a href='http://disqus.com' class='dsq-brlink'>comments powered by <span class='logo-disqus'>Disqus</span></a>
    ";

	}

	/**
	 * Returns the requested value from the class
	 * @param $key - the name of the value
	 *
	 * @return - the value associated with the key
	 */
	function __get( $key ){

		return $this->container[$key];

	}

}

?>