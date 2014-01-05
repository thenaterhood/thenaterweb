<?php

define("GNAT_ROOT", "engine");

include_once 'engine/lib/core_blog.php';
include_once 'phpunit.phar';

/**
 * This implicitly tests the VarGetter and sanitation 
 * classes as well, as the session object's data will 
 * be incorrect if either of those classes malfunctions.
 */
class requestTest extends PHPUnit_Framework_TestCase {

	protected function setUp(){

		$_SERVER['HTTP_HOST'] = 'test';
		$_SERVER['REQUEST_URI'] = 'test';

		$_GET['easytest'] = 'justSoEasy';
		$_GET['messyTest'] = '&n()ot.&?@#s*o^^@#.($*nice';
		$_GET['genericget'] = 'someData';
		$_GET['nonstring'] = False;

		$_POST['easyPostTest'] = 'justSoEasy';
		$_POST['messyPostTest'] = '&n()ot.&?@#s*o^^@#.($*nice';
		$_POST['genericpost'] = 'som##%e.d*@(@#&ata';

		$_COOKIE['easytest'] = 'pretty.easy';
		$_COOKIE['messy'] = 'u&*@$$npleasant';

		$_SERVER['HTTP_REFERER'] = 'someplace/there';


	}

	protected function tearDown(){


	}

	public function test_get_variable_retrieve(){


		$this->assertEquals( request::get('easytest'), 'justSoEasy' );
		$this->assertEquals( request::sanitized_get('easytest'), 'justSoEasy' );


	}

	public function test_get_variable_sanitization(){


		$this->assertEquals( request::get('messyTest'), '&n()ot.&?@#s*o^^@#.($*nice' );
		$this->assertEquals( request::sanitized_get('messyTest'), 'not.so.nice' );
	}

	public function test_post_variable_retrieve(){


		$this->assertEquals( request::post('easyPostTest'), 'justSoEasy' );
	}

	public function test_post_variable_sanitization(){


		$this->assertEquals( request::sanitized_post('messyPostTest'), 'not.so.nice' );
		$this->assertEquals( request::sanitized_post('easyPostTest'), 'justSoEasy' );
	}

	public function test_generic_retrieve_variable(){

		$this->assertEquals( request::get_sanitized( array('genericpost','genericget') )['genericpost'], 'some.data');
	}

	public function test_get_as_object(){

		$session = request::get_sanitized_as_object( array( 'easytest', 'messyTest', 'easyPostTest' ) );

		$this->assertTrue( is_object($session) );

		$this->assertEquals( $session->easytest, 'justSoEasy' );
		$this->assertEquals( $session->messyTest, 'not.so.nice' );
	}

	public function test_cookie_variable_retrieve(){

		$this->assertEquals( request::cookie('easytest'), 'pretty.easy');
		$this->assertEquals( request::sanitized_cookie( 'messy' ), 'unpleasant');
	}

	public function test_meta_data_retrieve(){

		$this->assertEquals( request::meta('HTTP_REFERER'), 'someplace/there');

	}

	public function test_get_default_value(){

		$this->assertEquals( request::default_value('id') , 'home');
	}

	public function test_nonexistant_variable_retrieve(){

		$this->assertEquals( request::get('foo') , '');
		$this->assertEquals( request::sanitized_get('foo') , '');

		$this->assertEquals( request::post('foo') , '');
		$this->assertEquals( request::sanitized_post('foo') , '');

		$this->assertEquals( request::cookie('foo') , '');
		$this->assertEquals( request::sanitized_cookie('foo') , '');

		$this->assertEquals( request::meta('foo'), '' );
		$this->assertEquals( request::sanitize(''), '' );

		$this->assertEquals( request::get_sanitized(array('foo'))['foo'], '' );

	}

	public function test_nonstring_retrieve(){

		$this->assertEquals( request::sanitized_get('nonstring'), '' );
		$this->assertEquals( request::sanitize(True, 2), '');

	}

	public function test_truncate_long_variable(){

		$this->assertEquals( request::sanitize('string', 2), 'st');
		$this->assertEquals( request::sanitize('string', 0), 'string');

	}

}


?>
