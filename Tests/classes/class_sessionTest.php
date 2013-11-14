<?php

define("GNAT_ROOT", "engine");

include_once 'engine/lib/core_blog.php';
include_once 'phpunit.phar';

/**
 * This implicitly tests the VarGetter and sanitation 
 * classes as well, as the session object's data will 
 * be incorrect if either of those classes malfunctions.
 */
class sessionTest extends PHPUnit_Framework_TestCase {

	protected function setUp(){

		$_SERVER['HTTP_HOST'] = 'test';
		$_SERVER['REQUEST_URI'] = 'test';

		$_GET['easytest'] = 'justSoEasy';
		$_GET['messyTest'] = '&n()ot.&?@#s*o^^@#.($*nice';

		$_POST['easyPostTest'] = 'justSoEasy';
		$_POST['messyPostTest'] = '&n()ot.&?@#s*o^^@#.($*nice';


	}

	protected function tearDown(){


	}

	public function test_get_easy(){

		$session = new session( array( 'easytest' ) );

		$this->assertEquals( $session->easytest, 'justSoEasy' );


	}

	public function test_get_messy(){

		$session = new session( array( 'easytest', 'messyTest', 'easyPostTest' ) );

		$this->assertEquals( $session->easytest, 'justSoEasy' );
		$this->assertEquals( $session->messyTest, 'not.so.nice' );
	}

	public function test_post_easy(){

		$session = new session( array( 'easyPostTest' ) );

		$this->assertEquals( $session->easyPostTest, 'justSoEasy' );
	}

	public function test_post_messy(){

		$session = new session( array( 'messyPostTest', 'easyPostTest' ) );

		$this->assertEquals( $session->messyPostTest, 'not.so.nice' );
		$this->assertEquals( $session->easyPostTest, 'justSoEasy' );
	}

}


?>