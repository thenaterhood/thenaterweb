<?php

use Naterweb\Routing\Urls\UrlHandler;

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

		$urlHandlerInstance = new UrlHandler();

		$this->assertEquals( $_GET['controller'], 'page' );
		$this->assertEquals( $_GET['id'], 'foo' );
		$this->assertEquals( $_GET['test'], 'bar' );

	}

	public function test_basic_url_reparse(){

		$_GET['url'] = 'page/foo/test/bar';

		$urlHandlerInstance = new UrlHandler();

		$this->assertEquals( $_GET['controller'], 'page' );

		$urlHandlerInstance->reparseUrl();

		$this->assertEquals( $_GET['controller'], 'foo' );
		$this->assertEquals( $_GET['id'], 'test' );
		$this->assertEquals( $_GET['bar'], '' );

	}

	public function test_nasty_url_parse(){

		$_GET['url'] = 'page#$#$%$#$/foo/test/bar%@#($#$&&';

		$urlHandlerInstance = new UrlHandler();

		$this->assertEquals( $_GET['id'], 'foo' );
		$this->assertEquals( $_GET['test'], 'bar%@#($#$&&' );



	}


	

}

?>
