3.0.1+0

^ Current production version number above ^

Master: [![Build Status](https://travis-ci.org/thenaterhood/thenaterweb.png?branch=master)](https://travis-ci.org/thenaterhood/thenaterweb)

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
folder. See also the wiki on Github which should have the most up-to-date 
documentation.

License
------------------
Licensed under the BSD License. See LICENSE for full license text.

Although not required by the license terms, please consider contributing 
back, offering feedback, or simply dropping a line to let Thenaterweb 
contributers know that you find it useful.


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
See https://github.com/thenaterhood/thenaterweb/wiki/Initial-Setup

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
a single slash (yoursite.com/whatever) requires its own controller. Thenaterweb 
has builtin applications for generating RSS/ATOM feeds and XML sitemaps. Sitemaps 
and feeds generated for an application can be viewed at yourdomain.com/sitemaps/YourApp 
and yourdomain.com/feeds/YourApp respectively. 

The builtin feed and sitemap apps can be disabled in the index.php file 
(see the wiki for more information) and will only show what your app 
provides to them, in the order provided. For feeds, you need to implement the 
getPostList() method, which returns a list of your posts, in the order 
to be shown in the feed, as instances of the article class. For sitemaps, 
you need to implement the getPageList() method, which should return 
an array with the paths to the files and the web url for the files, 
ie /server/path/to/file => yourdomain.com/YourApp/page. The sitemap 
app will do the rest.


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

v2.1.0+f9659

Improve database support

v3.0.0+0

Add database support, major code cleanup, better app support

- Add a data access layer with support for relational databases
	- Add support applications to add their own models
	- Add ability to create database schema

- Remove obsolete/unused code and files
- Remove app-specific code from engine
- Add builtin app for authentication
- Improve URL routing and application support
- Expand testing
- Move files to more portable locations 
	(engine/config/class_config.php -> settings.php)
- Add better support for handling uncaught exceptions
- Change documentation generator
- Improve UI of admin panel


v3.0.1+0

BUGFIX: fix friendly url not redirecting properly