<?php

/**
 * Description of ConfigTest
 *
 * @author molinspa
 */
class ConfigTest extends PHPUnit_Framework_TestCase
{
    public function testEnvironment()
    {
        $this->assertEquals(Config::getEnvironment(), Config::ENV_DEVELOPMENT);
    }
    
    public function testGetter()
    {
        $sDefaultValue = 'test.net';
        
        $this->assertNotNull(Config::get('app.url'));
        $this->assertNotEquals(Config::get('app.url', $sDefaultValue), $sDefaultValue);
        $this->assertEquals(Config::get('app.url53454', $sDefaultValue), $sDefaultValue);
        $this->assertNull(Config::get('fkdhguf'));
        $this->assertNull(Config::get('fkdhguf.flghf'));
    }
    
    public function testSetter()
    {
        $sNewKey = '5854fd5h4fdgfgds4fdfjkkbv';

        $this->assertEquals(Config::set('app.key', $sNewKey), $sNewKey);
        $this->assertEquals(Config::set('app.notExistsNfdfhds', $sNewKey), $sNewKey);
        $this->assertEquals(Config::set('ahghffgjhfhgf', $sNewKey), $sNewKey);
        $this->assertEquals(Config::set('cds.key', $sNewKey), $sNewKey);
    }
}