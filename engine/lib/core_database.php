<?php
/**
 * Combines all of the database includes
 * and miscellaneous functions into a single includable
 * file.
 * 
 */

include_once NWEB_ROOT.'/../settings.php';

use Naterweb\Engine\Configuration;

define('DB_TYPE',      Configuration::get_option( 'engine_storage_db' ) );
define('DB_USER',      Configuration::get_option( 'db_user' ) );
define('DB_PORT',      Configuration::get_option( 'db_port' ) );
define('DB_PASSWORD',  Configuration::get_option( 'db_password' ) );
define('DB_HOST',      Configuration::get_option( 'db_host' ) );
define('DB_NAME',      Configuration::get_option( 'db_name' ) );
define('ERROR_LEVEL',  Configuration::get_option( 'db_error_level' ) );

include_once NWEB_ROOT.'/lib/extern/php-database/Database.php';
include_once NWEB_ROOT.'/lib/extern/php-database/ErrorStack.php';


?>
