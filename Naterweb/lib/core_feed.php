<?php
/**
 * Contains utilities and classes for generating an atom feed. Relies
 * on the article class for retrieving and outputting post data in the
 * feed. Requires existing instances of the config and session classes.
 * 
 * @author Nate Levesque <public@thenaterhood.com>
 * Language: PHP
 * Filename: core_feed.php
 * 
 */

/**
 * Include the main blog functions and classes
 */
 include_once NWEB_ROOT.'/lib/core_blog.php';
 include_once NWEB_ROOT.'/Content/Generators/Syndication/AtomFeed.php';
 include_once NWEB_ROOT.'/Content/Generators/Syndication/RssFeed.php';

