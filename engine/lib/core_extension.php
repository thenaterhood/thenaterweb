<?php

include_once GNAT_ROOT.'/config/config_extensions.php';

/**
 * Initializes an array of extensions
 * @since 7/26/13
 * @author Nate Levesque <public@thenaterhood.com>
 */
function loadExtensions( $session, $registered ){

	$loaded = array();

	foreach ($registered as $extension ) {

		$loaded[$extension] = new $extension( $session );

	}

	return $loaded;

}

function getPreface( $loaded ){
	$string = "";

	foreach ($loaded as $ext) {
		$string = $string.$ext->getPrefaceCode();
		$string = $string."\n";
	}

	return $string;
}

function getPost( $loaded ){
	$string = "";

	foreach( $loaded as $ext ) {
		$string = $string.$ext->getPostCode();
		$string = $string."\n";
	}

	return $string;
}


?>