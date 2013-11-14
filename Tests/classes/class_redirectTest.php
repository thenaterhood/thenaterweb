<?php
define("GNAT_ROOT", "engine");

include_once 'engine/lib/core_redirect.php';
include_once 'phpunit.phar';

class redirectTest extends PHPUnit_Framework_TestCase {

	protected function setUp(){


	}

	protected function tearDown(){


	}

	public function test_initialize(){

		$redirect = new redirect( 'here', 'there' );

		$this->assertEquals( $redirect->toHtml(True), 'here to <a href=\'there\'>there</a>.' );

	}
	
}
?>