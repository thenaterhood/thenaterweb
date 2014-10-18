<?php
use Naterweb\Util\Lock;
class lockTest extends PHPUnit_Framework_TestCase {

	protected function setUp(){


	}

	protected function tearDown(){

		if( file_exists(\Naterweb\Engine\Configuration::get_option('dynamic_directory').'/index.php.lock' ) )
			unlink( \Naterweb\Engine\Configuration::get_option('dynamic_directory').'/index.php.lock' );


	}

	public function test_create_lock_file_exists(){

		$lock = new Lock( 'index.php' );

		$this->assertFalse( $lock->isLocked() );


	}

	public function test_create_lock_file_nonexist(){

		$lock = new Lock( 'NotReallyAFile' );

		$this->assertFalse( $lock->isLocked() );
	}

	public function test_lock_file(){

		$lock = new Lock( 'index.php' );

		$this->assertFalse( $lock->isLocked() );
		$lock->lock();

		$this->assertTrue( $lock->isLocked() );
		$this->assertTrue( file_exists(\Naterweb\Engine\Configuration::get_option('dynamic_directory').'/index.php.lock' ));

		$lock->unlock();
		$this->assertFalse( $lock->isLocked() );
		$this->assertFalse( file_exists(\Naterweb\Engine\Configuration::get_option('dynamic_directory').'/index.php.lock' ));


	}

	public function test_lock_finds_lock(){

		$lock = new Lock( 'index.php' );

		$this->assertFalse( $lock->isLocked() );
		$lock->lock();

		$this->assertTrue( $lock->isLocked() );

		$lock2 = new Lock('index.php');

		$this->assertTrue( $lock2->isLocked() );


	}

	public function test_lock_expired(){

		$handle = fopen( \Naterweb\Engine\Configuration::get_option('dynamic_directory').'/index.php.lock', 'w');
		fwrite( $handle, time()-240 );
		fclose( $handle );

		$lock = new Lock( 'index.php' );
		$this->assertFalse( $lock->isLocked() );

	}
	
}

?>
