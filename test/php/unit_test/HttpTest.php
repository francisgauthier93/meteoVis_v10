<?php

/**
 * Description of HttpTest
 *
 * @author molinspa
 */
class HttpTest extends PHPUnit_Framework_TestCase
{
    public function testIsHttps()
    {
        $this->assertFalse(Http::isHttps());
    }
    
    public function testGetCurrentUrl()
    {
        $this->assertNotEquals(Http::getCurrentUrl(), null);
        $this->assertNotEquals(strlen(Http::getCurrentUrl()), 0);
    }
}