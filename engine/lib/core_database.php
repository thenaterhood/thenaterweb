<?php
/**
 * Combines all of the database includes
 * and miscellaneous functions into a single includable
 * file.
 */
DEFINE ('DB_TYPE', getConfigOption( 'engine_storage_db' ) );
DEFINE ('DB_USER', getConfigOption( 'db_user' ) );
DEFINE ('DB_PASSWORD', getConfigOption( 'db_password' ) );
DEFINE ('DB_HOST', getConfigOption( 'db_host' ) );
DEFINE ('DB_NAME', getConfigOption( 'db_name' ) );
DEFINE ('ERROR_LEVEL', getConfigOption( 'db_error_level' ) );

include_once GNAT_ROOT.'/lib/extern/php-database/Database.php';
include_once GNAT_ROOT.'/lib/extern/php-database/ErrorStack.php';


?>
