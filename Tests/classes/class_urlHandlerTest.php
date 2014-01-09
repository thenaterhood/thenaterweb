<?php 
include_once 'phpunit.phar';

class urlHandlerTest extends PHPUnit_Framework_TestCase {

	protected $urlHandlerInstance;

	protected function setUp(){

		$_SERVER['HTTP_HOST'] = 'test';
		$_SERVER['REQUEST_URI'] = 'test';


	}

	protected function tearDown(){


	}

	public function test_basic_url_parse(){

		$_GET['url'] = 'page/foo/test/bar';

		$urlHandlerInstance = new urlHandler();

		$this->assertEquals( $urlHandlerInstance->getControllerId(), 'page' );
		$this->assertEquals( $_GET['id'], 'foo' );
		$this->assertEquals( $_GET['test'], 'bar' );

	}

	public function test_basic_url_reparse(){

		$_GET['url'] = 'page/foo/test/bar';

		$urlHandlerInstance = new urlHandler();

		$this->assertEquals( $urlHandlerInstance->getControllerId(), 'page' );

		$urlHandlerInstance->reparseUrl();

		$this->assertEquals( $urlHandlerInstance->getControllerId(), 'foo' );
		$this->assertEquals( $_GET['id'], 'test' );
		$this->assertEquals( $_GET['bar'], '' );

	}

	public function test_nasty_url_parse(){

		$_GET['url'] = 'page#$#$%$#$/foo/test/bar%@#($#$&&';

		$urlHandlerInstance = new urlHandler();

		$this->assertEquals( $urlHandlerInstance->getControllerId(), 'page' );
		$this->assertEquals( $_GET['id'], 'foo' );
		$this->assertEquals( $_GET['test'], 'bar%@#($#$&&' );



	}


	

}

?>