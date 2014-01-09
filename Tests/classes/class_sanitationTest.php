<?php
include_once 'phpunit.phar';

class sanitationTest extends PHPUnit_Framework_Testcase {

	protected function setUp(){

	}

	protected function tearDown(){

	}

	public function test_clean_string(){

		$easytest = 'justSoEasy';

		$sanitation = new sanitation( $easytest, 200 );

		$this->assertEquals( $sanitation->str, 'justSoEasy' );

	}

	public function test_messy_string(){

		$messyTest = '&n()ot.&?@#s*o^^@#.($*nice';

		$sanitation = new sanitation( $messyTest, 200 );

		$this->assertEquals( $sanitation->str, 'not.so.nice');


	}

	public function test_simple_truncation(){

		$easyLongTest = 'just a long, easy test to check truncating works';

		$sanitation = new sanitation( $easyLongTest, 4 );

		$this->assertEquals( $sanitation->str, 'just');

	}

	public function test_messy_truncation(){

		$messyLongTest = 'a )&@)*#&$long*& m)(&*essy)(&))&%$$ test';

		$sanitation = new sanitation( $messyLongTest, 6 );

		$this->assertEquals( $sanitation->str, 'a long');



	}

	public function test_html_escape(){

		$unsafeHtml = '<p>Bad html</p>';

		$sanitation = new sanitation( $unsafeHtml, 200 );

		$this->assertEquals( $sanitation->str, 'pBad htmlp' );

	}

	public function test_int_cleanup(){

		$rawInt = '257%&@';

		$sanitation = new sanitation( $rawInt, 30);

		$this->assertEquals( $sanitation->num, 257 );
	}

	public function test_bool_cleanup(){

		$rawBool = '&True';

		$sanitation = new sanitation( $rawBool, 30 );

		$this->assertTrue( $sanitation->boo );
	}
	
}
?>
