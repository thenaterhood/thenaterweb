<?php

namespace Naterweb\Engine;
/**
 * Contains internal naterweb functionality for 
 * handling some generic internal features such as 
 * error handling.
 */
class Applications{
    
    private static $configuration;
    private static $CONFIG_LOADED = false;
    private static $aliases;
    private static $installed;
        
        public static function setup_aliases( $aliases ){
            
            self::$aliases = $aliases;
            
        }
        
        public static function setup_installed( $installed ){
            
            self::$installed = $installed;
            
        }
        
        public static function get_app( $appname ){
            
            if (array_key_exists($appname, self::$aliases)){
                $appname = self::$aliases[$appname];
            }
            
            if (array_key_exists($appname, self::$installed)){  
                return array('name'=>$appname, 'root'=>self::$installed[$appname]);
            }
            else{
                return array('name'=>'', 'root'=>'');
            }
            
        }
        
        public static function get_controllers(){
            return self::$installed;
        }

}

?>
