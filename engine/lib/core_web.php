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
include NWEB_ROOT.'/Routing/Urls/class_urlHandler.php';
include NWEB_ROOT.'/lib/core_database.php';
include NWEB_ROOT.'/Client/class_sessionMgr.php';
include NWEB_ROOT.'/Client/class_request.php';
include NWEB_ROOT.'/classes/class_dataAccessLayer.php';
include NWEB_ROOT.'/classes/class_modelBase.php';
include NWEB_ROOT.'/classes/class_engineErrorHandler.php';
include NWEB_ROOT.'/lib/core_httpstatus.php';
include NWEB_ROOT.'/Routing/Urls/class_urlBuilder.php';
include NWEB_ROOT.'/Engine/class_applications.php';

include NWEB_ROOT.'/Content/Loaders/class_contentFactory.php';
include NWEB_ROOT.'/Content/Renderers/class_phpRenderer.php';

