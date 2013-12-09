Developing On Thenaterweb
=========================

Thenaterweb comes with a web administration interface, 
blog, and basic page system out of the box. These can be 
used as examples of how developing on the framework works.

Development Crash Course
-------------------------

All applications that run on top of Thenaterweb are expected 
to be in a subdirectory of the controller directory in the 
web root. The name of the directory will be the URL for 
the application. Contained in that directory should be a file 
called main.php which contains a controller class extending 
controllerBase. Configuration files should be kept in the same 
directory. Thenaterweb's main index file includes the majority 
of Thenaterweb's software as well as controllerBase, so your 
main.php file does not need to do any of this. The constructor 
of your application's main class should set up some data and 
otherwise initialize the class. If your application has a config 
file, it should be set here and the reader for it should load it. 
A configuration file is not required, but allows for management 
of your application's settings via the webadmin panel.

All in all, the structure of an application should look like:

	web root
		|
		+ index.php (thenaterweb's index)
		+ engine (thenaterweb engine directory)
    	|
    	+ controller
          |
          + YourApp
             |
             + main.php (with class YourApp extending controllerBase)
             + conf.xml (named as you wish, if you use a config file)
             + other files

In this case, the URL to access the main (front) page would be:

	yourdomain.com/YourApp

And associated pages (or views) would be accessed as:

	yourdomain.com/YourApp/PageName

On loading pages, Thenaterweb first looks for the controller 
directory then includes the main.php file and initializes the 
controller and requests it for the filename of a template 
to include to display the view. Controllers are expected to 
have their own template (or view) that handles URL related 
data that the application needs. This can be done via Thenaterweb's 
URL data system (explained later). If no template is specified, 
Thenaterweb will default to Thenaterweb's builtin template. This 
template should also handle retrieving the file containing logic or 
html for the view. Thenaterweb's main index file will retrieve the 
template from the controller and include it as-is. In order to set 
variables for the template to use, the variables can be defined 
prior to the controller class.

Template loading example:
	
	requested URL: yourdomain.com/YourApp/ViewName

	index.php:
		|
		+ search controllers for YourApp
		+ include YourApp/main.php
		+ initialize YourApp/main.php YourApp() class
		+ run $controller->ViewName() or include $controller->template 

Naming the application's configuration file conf.xml (or simply not 
having one at all) is entirely optional.

Providing that the controller is stored in the proper place, Thenaterweb 
places no restrictions on where the associated data is stored and will 
retrieve the configuration data if need be to find it.

Although optional, Thenaterweb provides some builtin utilities for 
your application that may be useful, which include feed and sitemap 
generation. These exist already at YourSite.com/sitemaps/YourApp and 
YourSite.com/feeds/YourApp (more details in the Provided Utilities section). 
In order to make use of these, you must override two methods that exist 
in the base controller class that your application extends.

Feeds: You must override the class method getPostList(). This method should 
return a list of instances of the builtin article class in the order 
they should appear in the feed. The article class can be initialized directly 
from a file or a database table and the appid, or can be initialized by 
way of the stdClassArticle class (which accepts an stdClass object containing 
the post data) or by way of the mappedArticle class which accepts an 
associative array.

Sitemaps: You must override the class method getPageList(). This method 
should return an associative array of the pages you would like to 
show in the sitemap. The array should be of the form LocalFile => WebPath.

Provided Utilities
-----------------------

Session Object
--------------

The session class is not required to be 
used in your applications. It provides a way of retrieving 
session variables and sanitizing them. The data sanitization 
is fairly strict so you may need to account for that if you 
intend to use it.

Usage:

	$session = new session( array( 'variable1', 'variable2' ) );
	cleanVar1 = $session->variable1;

You can use as many session objects as you see fit. On initialization, 
the session object uses VarGetter instances to search for the 
desired variable in the GET, POST, and COOKIE arrays.

VarGetter Object
----------------

The VarGetter provides an interface for retrieving single variables and 
sanitizing them. It can be called with only the name of a variable to 
locate the variable in any of the 3 arrays POST, GET, and COOKIE, or 
one of the 3 can be specified in which case it will only search there. 

Retrieved variables can be retrieved as various types.

Usage:

	$var = new varGetter( 'VariableName', [ method, maximum length ] );
	$VariableName = $var->str;


Sitemap Creation
-----------------

Thenaterweb provides facilities for automatically generating sitemaps 
for the pages in applications. These sitemaps can be accessed at 

	yourdomain.com/sitemaps/YourApp

In order to make use of these, your controller must be able to return 
a field called page_directory (which must be stored in the controllerBase 
settings array). The sitemap generator will iterate through this directory 
and create an xml sitemap that includes all of the contained views 
who's files are prefaced with page_viewName.php and will use the viewName 
as the page name.

You can also create your own sitemaps using the core_sitemaps (include 
engine/lib/core_sitemap.php) functions or directly using the sitemap class 
(include engine/classes/class_sitemap.php)

Feed Generation
------------------

Similarly, Thenaterweb provides facilities for generating rss/atom feeds. 
These can be accessed at 

	yourdomain.com/feeds/YourApp

Or for RSS

	yourdomain.com/feeds/YourApp/type/rss

In order for this to work, your application must be able to return a field, 
once again contained in the $settings array of the controller base called 
post_directory. Thenaterweb will create an index of your posts in json format 
stored in engine/var as well as a feed cache of your posts in json format 
in the same place. These will be automatically updated as necessary without 
any required input from you. To regenerate them from scratch (this may also 
be required for initially creating them) visit:

	yourdomain.com/feeds/YourApp/regen/True

Initial creation and regeneration may take a substantial amount of time if 
there are a lot of posts and may exceed PHP's maximum execution time. For 
this reason, if you are initially setting up Thenaterweb with a large number 
of posts, it may be a good idea to visit the feed after adding posts in groups 
so that the inventory and feed can be created without exceeding PHP's 
execution time.

Similarly to sitemaps, you can create your own feed system using 
Thenaterweb's feed system using the core_feed (include engine/lib/core_feed.php) 
functions or by using the feed class directly (include engine/classes/class_feed.php).

Displaying Pages
--------------------

To display pages and posts within your template (as the builtin 
system does), you may want to make use of the article class (included already). 
The article class accepts a filename as an argument and will identify it based 
on file extension (supported: json, pre[formatted text], html, and php) and will 
read and parse it if necessary. It then allows for including the file or 
displaying it in a form appropriate to the format. Generally you would 
use this (if you may have PHP pages) as:

	$article = new article( 'yourFilename', 'yourAppName' );

	if ( $article->isPhp() ){
		include $article->getFile();
	} else {
		echo $article->toHtml();
	}

Additional output formats supported by the article class are atom, rss, 
list_item_output. The article class assumes that blog posts are stored 
as json files, so json files will be displayed as blog posts with a date, 
tags, title, etc.

Thenaterweb has some included pages for blog use stored in engine/lib/pages. 
These include a blog homepage with a list of posts, a post page, tags pages, 
and a titles page. These are, respectively, page_home.php, page_read.php, 
page_tags.php/page_taghistogram.php, page_titles.php. The contained blog 
platform will use these if they are not already existant in the blog page 
directory. You may find them useful for your own blog application if you are 
making use of the builtin feed system. These builtin pages rely on the 
inventory file generated alongside the feed so you may want to use the builtin 
feed system or manually manage the json inventory (via the inventory class which 
is included by the main index - engine/classes/class_inventory.php) if 
you intend to use them.
