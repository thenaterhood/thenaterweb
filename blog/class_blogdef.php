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
		$this->container['post_directory'] = '/var/www/blog/entries';

		# Configure the identity of the blog for use when generating
		# urls to the blog and saving files. This is publicly visible.
		$this->container['id'] = 'blog';

		# Configure the title of the blog. This is publically visible
		# and is used to brand the feed and blog pages.
		$this->container['title'] = "The Philosophy of Nate";

		# Set a catchline for the blog. This is publically visible
		# and is used in the feed.
		$this->container['catchline'] = "It's the cyber age. Stay in the know.";

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