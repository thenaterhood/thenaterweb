<?php

include_once GNAT_ROOT.'/classes/class_jsondb.php';
include_once GNAT_ROOT.'/classes/class_sqlitedb.php';

class DatabaseFactory{


	public static function create( $type, $dbname ){


		switch ($type) {
			case 'sqlite':
				return new SQLiteDb( $dbname.'.db' );
			
			default:
				return new JsonDb( $dbname.'.json' );
		}



	}
	

}

?>