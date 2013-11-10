2.0.1+90cea

^ Current version number above ^

Thenaterweb
===================
Thenaterweb is a PHP MVC framework for small to medium sized websites. 
It does not require a database of any kind (though will have support 
in the future). It was originally intended to be a small project devoted 
specifically to running my website, but has grown and evolved to a point 
where it may be usable to others as well. Excuse the occasional pages 
from my site that may be included in commits throughout.

For information about developing applications that run on Thenaterweb, 
see the DEVELOPING.md file and phpdoc documentation in the documentation 
folder.

License
------------------
Licensed under the GPLv2. See license.txt for full license text.


Project Brief
------------------
Thenaterweb was originally a small project for learning PHP, with the end 
goal of writing a simple (...very simple) website that had more power 
and was easier to manage than a collection of html pages. As it grew in 
functionality, more sections of the site were migrated into the system 
to provide functionality for blogs, sitemaps, and MVC functionality. 

It was developed to be relatively simple, flexible, and to have well-
documented code.

Main development for the framework is on the master branch with 
stable points tagged with a version number. The master branch is 
merged into the Production branch at each release tag. Versious and 
commits prior to v2.0.0 will probably contain pages and settings from 
the live version of my website (at those points in time). Newer commits 
may have some vestiges of that as well.

Using Thenaterweb
===================
Thenaterweb requires PHP > 5 and write access to a directory (for logs and 
indexes), as well as the availability of .htaccess or server configurations. 
Note that the .htaccess requirement may change in the future as the engine 
design is adapted more to the MVC design (it was originally not MVC).

Setup
-------------------
Assuming that the basic setup works, there is no need to adjust the 
prepend.php or .htaccess prepend path. However, if the engine is going to 
be stored anywhere other than the web root, the two need to be adjusted. 
Note that putting the engine somewhere other than the web root is untested 
at this point. In any case, the engine/config/class_config.php file also 
needs to be adjusted, otherwise generated URLs will be incorrect.

The engine loads controllers for site sections in order to display 
pages. These are expected to be located in controllers. They contain 
configuration data for page and post locations as well as various other 
functionality depending on the controller. Shipped with Thenaterweb are 
controllers for pages (yoursite.com/page/somePage), a blog 
(yoursite.com/blog) and a feed (yoursite.com/feed). Paths in these will 
need to be adjusted to the actual locations of things.

Controllers may read additional configuration files to determine the 
locations of items. These are located in engine/config/section.d/controller.conf.xml. 
The XML files there will need to be adjusted so paths point to the correct 
locations. This is an artifact of the previously non-MVC design of the 
engine, and may change in the future.

Thenaterweb requires no additional setup for the addition of controllers. 
Rather, when a location on the site is accessed, i.e yoursite.com/SomeName, 
Thenaterweb will look in the controllers directory for a file with the name 
control_SomeName.php. The control classes should extend the controllerBase class 
from the controller/interface_control.php file. If Thenaterweb doesn't find 
a file that matches the requested controller, it will display a 404 error page.

You should also take a look at the engine/config/class_config.php file which 
contains various other settings as well.

The default page template (based on bootstrap) is in 
engine/config/template.d/page_template.php.

URL Handling
--------------------
URLs that point to (existing) files will not be touched by the mod_rewrite 
rules that Thenaterweb has by default. Directories will for the time being, 
until more adjustments are made on my end to accomodate the new MVC design.

URLs are handled as key/value pairs after the first two items. So, for example, 
yoursite.com/blog/read/node/postName maps to:

	controller => blog
	id => read
	node => postName

and on down. If you don't have the .htaccess rewrite module or you turn off 
friendly URLs in the config file, your URLs will look slightly different:

	yoursite.com/?url=blog/read/node/postName

Thenaterweb parses the contents of $_GET['url'] and puts the key/value 
pairs into the $_GET superglobal for handling by the builtin session data 
retriever (which will sanitize as well) or your own data handler.

Note that if you manually turn off your server's rewrite module or if it is 
unavailable, you MUST turn off friendly urls in Thenaterweb's config. Otherwise, 
Thenaterweb will force a 301 redirect to the "friendly" URL which it expects 
mod_rewrite to rewrite into a compatible URL which will fail without it.

Blogs and Pages and Controllers, Oh My!
------------------------
No lions or tigers or bears though.

Each set of pages or each item that can be accessed on your site after 
a single slash (yoursite.com/whatever) requires its own controller. Config data 
for these controllers must be placed in engine/config/section.d/whatever.conf.xml, 
where it is expected to be by various Thenaterweb builtins such as the feed system.

For blogs, the feed system will load the configuration file from the engine 
and construct the feed from the posts it finds at the location specified. 
At the same time, it will produce a summary of posts that can be used via 
the inventory class to produce lists of tags or titles of posts. 

The engine supports displaying pages stored as preformatted text (.pre), 
PHP (.php), html (.html), and JSON (.json), if you're using engine builtins. 
Loading and displaying these is managed by the article class which can output 
in a variety of formats. The article class will simply include PHP files in 
the page, but will echo HTML (so no PHP embedded in a .html file will work), 
parse Json files, and place preformatted text between pre tags after sanitizing 
it.

The JSON format for files contains the following fields, which are parsed as an 
object:

	title : "Page Title"
	tags : "page,tags,here"
	content: "the actual content of the page, in html format"
	datestamp: "the modification date"

The article class will load these into html and display it.

The framework has prebuilt pages for showing blog tags, titles, and posts in 
engine/lib/pages. It will look first in the page directory for the blog/page 
defined in the conf.xml file for that controller and load it if it exists, 
and if not will load from the builtin pages. Pages can be added to sites or blogs 
by adding them to the designated directory for pages for the controller. Blog posts 
can be stored in a few different formats (though .json is preferred) as with pages 
in the post directory configured for the blog in the .conf.xml file. Pages are 
expected to be stored with the filenames page_pageName.html or hidden_pageName.html. 
Pages prefaced with hidden_ are not shown in the sitemap.

Engine Data
-------------------
The engine is configured (unless you change it) to store various cache-type 
files in engine/var/dynamic. These follow a few conventions:

	xxx.lock - a mutex for other files. They expire after 30 seconds, the default PHP timeout.
	xxx.inventory.json - a searchable array of posts on a blog.
	xxx.feed.json - feed data for a blog.

The names are the path to the file or directory they apply to with 
any forward slash replaced with an underscore.

These files are maintained by the engine and are updated automatically. Since 
they depend on the locations of where things are, they may not carry between 
different configurations. If they get corrupted, they can simply be removed 
or the engine told to regenerate them (/feed/YourBlog/regen/true will regenerate 
the feed and the inventory from scratch).

Version Change Summaries (Oldest to Newest)
==================

v1.1.0+cdaac

Initial stable release

v1.2.0+54c63

New blog layout, improved compatibility

v1.3.0+6dedce

Update inline documentation and bugfixes


v1.4.0+de9f5

Add initial support for "friendly" urls

- Add redirection classes

v1.5.0+d7a5f

Documentation cleanup and engine refactoring

v1.5.1+45975

Bugfixes to data sanitization and array access

v1.6.0+5b3d5

Refactoring

- Refactor blog directories for consistency
- Move additional html into static files
- Add dataMonger class to improve consistency of data management

v1.7.0+298f0

Overhaul inventory and variable management, class reorganization

v1.7.1+38f46

Bugfixes

- Rewrite suggestions to use inventory
- Fix array access in posts

v1.7.2+3d607

Bugfixes

- core_feed.php updated to proper post location
- post page index link corrected

v1.8.0+aa0c2

Improvements to blog platform flexibility

- Blog platform now supports multiple blogs
- Improvements to page generation and feed management

v1.9.0+6d11b

Introduce webadmin panel, improvements to data management

- Introduce webadmin panel
- Add file mutexing capabilities
- Bugfixes
- Code standards improvements

v1.10.0+f7c90

Refactoring and data management improvements

- Refactor engine directories
- Add prepends to define engine location
- Speed improvements to data management in feed and inventory
- Code style improvements

v1.10.1+11f03

Performance improvements

- Update to repair inventory related performance issues

v1.11.0+63dca

Unify feed and inventory data management, bugfixes

- Move indexing (rss/atom and inventory) storage to dedicated class
- Fix issue with adding and removing items from inventory and feeds
- Update lib/core_feed.php to interact with new feed system

v1.12.0+a5d06

Improvements to content management and inventories

- All content now loads via the article class
- Support for json pages and html/txt/php blog posts


v1.13.0+208ef

Improvements to API, centralizing blog configuration data, improvements to webadmin

- Fixes to inventory API
- Convert blogdef data to xml and move to central location
- Fix bugs with webadmin and updates for changes to blog configuration

v2.0.0+e473a

Now uses MVC design scheme


v2.0.1+90cea

Update webadmin panel for compatbility with new changes