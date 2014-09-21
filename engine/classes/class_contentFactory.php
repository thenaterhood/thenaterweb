<?php

namespace Naterweb\Content\Loaders;

class ContentFactory{
	
	public static function loadContentFile( $file, $type_override=False ){

		if (!$type_override){
			$type = self::getContentType($file);
		} else {
			$type = $type_override;
		}

		$loader = strtolower($type) . "Loader";

		if ( $type_override != 'std' && !file_exists($file) ){
			throw new \Exception("File ($file) not found");
		} 

		if (file_exists(NWEB_ROOT."/ContentLoaders/class_$loader.php")){
			require_once(NWEB_ROOT."/ContentLoaders/class_$loader.php");
			$loader = "Naterweb\Content\Loaders\\".ucfirst($loader);
			return new $loader($file);
		} else {
			throw new \Exception("Unsupported content type");
		}

	}

	public static function loadContent( $content, $type_override=False ){



	}

	private static function getContentType( $file ){
		$path = pathinfo( $file );
		return $path['extension'];

	}

}
