<?php

/**
 * Description of core_webTest
 *
 * @author nate
 */
class core_webTest extends PHPUnit_Framework_TestCase {
    
    protected function setUp(){
        
    }
    
    protected function tearDown(){
        
    }
    
    /**
     * @expectedException Exception
     * @expectedExceptionmessage Template could not be loaded.
     */
    public function test_render_php_template_invalid(){
        
        render_php_template('foo', array() );
        
        
        
    }
    
        /**
     * @expectedException Exception
     * @expectedExceptionmessage Template could not be loaded.
     */
    public function test_render_php_template_badfile(){
        
        render_php_template('foo', array(), False );
        
        
        
    }
    
    
    public function test_render_php_template(){
        
        render_php_template(NWEB_ROOT.'/../Tests/test-data/template.php', array('data'=>'foo'), False);
        
        $this->assertEquals( 'foo', recieved );
    }
    
    
    //put your code here
}
