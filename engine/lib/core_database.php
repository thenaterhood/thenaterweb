<?php
/**
 * Combines all of the database includes
 * and miscellaneous functions into a single includable
 * file.
 * 
 */

include_once NWEB_ROOT.'/classes/class_engine.php';

define('DB_TYPE',      Engine::get_option( 'engine_storage_db' ) );
define('DB_USER',      Engine::get_option( 'db_user' ) );
define('DB_PORT',      Engine::get_option( 'db_port' ) );
define('DB_PASSWORD',  Engine::get_option( 'db_password' ) );
define('DB_HOST',      Engine::get_option( 'db_host' ) );
define('DB_NAME',      Engine::get_option( 'db_name' ) );
define('ERROR_LEVEL',  Engine::get_option( 'db_error_level' ) );

include_once NWEB_ROOT.'/lib/extern/php-database/Database.php';
include_once NWEB_ROOT.'/lib/extern/php-database/ErrorStack.php';


?>
