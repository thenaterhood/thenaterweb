<?php
include_once 'phpunit.phar';

class redirectTest extends PHPUnit_Framework_TestCase {

	protected function setUp(){


	}

	protected function tearDown(){


	}

	public function test_initialize(){

		$redirect = new redirect( 'here', 'there' );

		$this->assertEquals( $redirect->toHtml(True), 'here to <a href=\'there\'>there</a>.' );
		$this->assertEquals( $redirect->view(), 'here to there' );


	}

	/**
	 * @expectedException PHPUnit_Framework_Error_Warning
	 * @expectedExceptionMessage Cannot modify header information - headers already sent by
	 */
	public function test_301_apply(){

		$redirect = new redirect( 'here', 'there' );
		$redirect->apply(301);


	}

	/**
	 * @expectedException PHPUnit_Framework_Error_Warning
	 * @expectedExceptionMessage Cannot modify header information - headers already sent by
	 */
	public function test_302_apply(){

		$redirect = new redirect( 'here', 'there' );
		$redirect->apply(302);


	}


	
}
?>