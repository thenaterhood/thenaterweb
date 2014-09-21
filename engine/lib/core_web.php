<?php
/**
 * 
 * Contains functions for basic web capabilities such as reading
 * variables from the URL (safely), setting/getting cookies and config
 * options.
 * @author Nate Levesque <public@thenaterhood.com>
 * @copyright Nate Levesque 2013
 * Language: PHP
 * Filename: core_web.php
 * 
 */

/**
 * Include the config file
 */
include NWEB_ROOT.'/../settings.php';
include NWEB_ROOT.'/classes/class_lock.php';
include NWEB_ROOT.'/lib/core_extension.php';
include NWEB_ROOT.'/classes/class_urlHandler.php';
include NWEB_ROOT.'/lib/core_database.php';
include NWEB_ROOT.'/classes/class_sessionMgr.php';
include NWEB_ROOT.'/classes/class_request.php';
include NWEB_ROOT.'/classes/class_dataAccessLayer.php';
include NWEB_ROOT.'/classes/class_modelBase.php';
include NWEB_ROOT.'/classes/class_engineErrorHandler.php';
include NWEB_ROOT.'/lib/core_httpstatus.php';
include_once NWEB_ROOT.'/classes/class_engine.php';

require_once NWEB_ROOT.'/classes/class_contentFactory.php';

/**
* Checks to see if the preferred file exists, and if it does
* returns it, otherwise it returns the secondary file, which ideally
* should be a file (like an error page) that is guaranteed to exist.
* 
* @param $preferred (string): the preferred page to include
* 
* 
*/
function pullContent( $preferred ){

	return Naterweb\Content\Loaders\ContentFactory::loadContentFile($preferred);
	
}

function render_php_template( $template, $pagedata, $use_csrf=True ){

	$page = (object)$pagedata;
        
    if ( $use_csrf ){
        $sessionmgr = SessionMgr::getInstance();

        $page->csrf_token = $sessionmgr->get_csrf_token();
        $page->csrf_key = $sessionmgr->get_csrf_id();
    }

	if ( file_exists($template) ){
		include $template;
	} else {
		throw new Exception('Template could not be loaded.');
	}

}

/**
 * Built to abstract retrieving config variables, since
 * they're now contained in a class this is just for legacy
 * support until everything else gets moved off
 * of using this function
 * 
 * @param $key - the name of a config key to retrieve
 * 
 * @return - the value of the config key
 * 
 * @deprecated 1/8/2014 - deprecated in favor of 
 *  directly calling engine::get_option, as this is now 
 *  a waste of lines of code.
 */
function getConfigOption($key){
        // This extra include is necessary in order for 
        // phpunit tests to work as expected. Why is beyond 
        // me.
	include_once NWEB_ROOT.'/classes/class_engine.php';
	return Engine::get_option($key);
}
	

?>
