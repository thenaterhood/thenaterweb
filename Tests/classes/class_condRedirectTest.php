<?php

class condRedirectTest extends PHPUnit_Framework_TestCase {

	protected function setUp(){


	}

	protected function tearDown(){


	}

	public function test_initialize(){

		$redirect = new condRedirect( 'here', 'there', 'here' );

		$this->assertEquals( $redirect->toHtml(True), 'here to <a href=\'there\'>there</a>.' );
		$this->assertEquals( $redirect->view(), 'here to there' );


	}

	/**
	 * @expectedException PHPUnit_Framework_Error_Warning
	 * @expectedExceptionMessage Cannot modify header information - headers already sent by
	 */
	public function test_301_apply(){

		$redirect = new condRedirect( 'here', 'there', 'here' );
		$redirect->apply(301);


	}

	/**
	 * 
	 */
	public function test_301_no_apply(){

		$redirect = new condRedirect( 'here', 'here', 'here' );
		$redirect->apply(301);



	}

	/**
	 * @expectedException PHPUnit_Framework_Error_Warning
	 * @expectedExceptionMessage Cannot modify header information - headers already sent by
	 */
	public function test_302_apply(){

		$redirect = new condRedirect( 'here', 'there', 'anywhere' );
		$redirect->apply(302);


	}

	/**
	 * 
	 */
	public function test_302_no_apply(){


		$redirect = new condRedirect( 'here', 'here', 'here' );
		$redirect->apply(302);


	}


	
}
?>