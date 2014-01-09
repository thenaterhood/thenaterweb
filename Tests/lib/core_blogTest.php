<?php

/**
 * Description of core_webTest
 *
 * @author nate
 */
class core_blogTest extends PHPUnit_Framework_TestCase {
    
    protected function setUp(){
        
    }
    
    protected function tearDown(){
        
    }
    
    public function test_random_item(){
        
        $array = array('foo'=>'bar');
        
        $this->assertTrue( in_array(RandomItem( $array), $array ));
        
    }
    
    public function test_array_to_object(){
        
        $array = array('foo'=>'bar', 'open'=>'close');
        
        $obj = RecArrayToObject( $array );
        
        $this->assertTrue( is_object( $obj ) );
        $this->assertEquals( 'bar', $obj->foo );
        $this->assertEquals( 'close', $obj->open );
        
        
    }
    
}
