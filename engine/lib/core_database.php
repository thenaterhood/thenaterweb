<?php
/**
 * Combines all of the database includes
 * and miscellaneous functions into a single includable
 * file.
 */
DEFINE ('DB_TYPE',      Engine::get_option( 'engine_storage_db' ) );
DEFINE ('DB_USER',      Engine::get_option( 'db_user' ) );
DEFINE ('DB_PORT',      Engine::get_option( 'db_port' ) );
DEFINE ('DB_PASSWORD',  Engine::get_option( 'db_password' ) );
DEFINE ('DB_HOST',      Engine::get_option( 'db_host' ) );
DEFINE ('DB_NAME',      Engine::get_option( 'db_name' ) );
DEFINE ('ERROR_LEVEL',  Engine::get_option( 'db_error_level' ) );

include_once NWEB_ROOT.'/lib/extern/php-database/Database.php';
include_once NWEB_ROOT.'/lib/extern/php-database/ErrorStack.php';


?>
