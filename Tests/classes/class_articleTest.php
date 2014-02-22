<?php 

class articleTest extends PHPUnit_Framework_TestCase {

	protected $urlHandlerInstance;

	protected function setUp(){

		$article = array();
		$article['title'] = 'Test';
		$article['tags'] = 'foo,bar';
		$article['link'] = 'www.test.com';
		$article['content'] = 'test article content';
		$article['datestamp'] = '';


		$handle = fopen(NWEB_ROOT.'/../Tests/test-data/article.json', 'w');
		fwrite( $handle, json_encode($article, True) );
		fclose( $handle );

	}

	protected function tearDown(){

		unlink( NWEB_ROOT.'/../Tests/test-data/article.json');


	}

	public function test_read_json(){

		$article = new Article( NWEB_ROOT.'/../Tests/test-data/article', 'test/uri' );

		$this->assertEquals( $article->title, 'Test' );
		$this->assertEquals( $article->tags, 'foo,bar' );
		$this->assertEquals( $article->getType(), 'json' );
		$this->assertFalse( $article->isPhp() );


	}

	public function test_read_nonexistant(){

		$article = new Article( 'Tests/test-data/foo', 'test/uri' );

		$this->assertEquals( $article->title, 'Holy 404, Batman!' );

		
	}
	

}

?>