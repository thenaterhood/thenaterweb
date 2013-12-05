<?php
/**
 * Combines all of the database includes
 * and miscellaneous functions into a single includable
 * file.
 */
DEFINE ('DB_TYPE', $CONFIG->engine_storage_db);
DEFINE ('DB_USER', $CONFIG->db_user);
DEFINE ('DB_PASSWORD', $CONFIG->db_password);
DEFINE ('DB_HOST', $CONFIG->db_host);
DEFINE ('DB_NAME', $CONFIG->db_name);
DEFINE ('ERROR_LEVEL', $CONFIG->db_error_level);

include_once GNAT_ROOT.'/lib/extern/php-database/Database.php';
include_once GNAT_ROOT.'/lib/extern/php-database/ErrorStack.php';


?>
