<?php

/**
 * Description of SuperglobalTest
 *
 * @author molinspa
 */
class SuperglobalTest extends PHPUnit_Framework_TestCase
{
    public function testIsServerKey()
    {
        $this->assertTrue(Superglobal::isServerKey('REQUEST_TIME'));
        $this->assertTrue(Superglobal::isServerKey('HOME'));

        $this->assertFalse(Superglobal::isServerKey('NO_NAME_TEST'));
        $this->assertFalse(Superglobal::isServerKey(''));
    }
    
    public function testGetServerValueByKey()
    {
        $this->assertEquals(Superglobal::getServerValueByKey('HOME'), '/u/molinspa');
        $this->assertEquals(Superglobal::getServerValueByKey('USER'), 'molinspa');
    }
    
    public function testIsGetKey()
    {
        $_GET['iTest'] = 9;
        $_GET['sString'] = 'fdgdfhgljnvcurhtufjdc';
        $_GET['sString2'] = '';
        $_GET['bBool1'] = true;
        $_GET['bBool2'] = false;

        $this->assertTrue(Superglobal::isGetKey('iTest'));
        $this->assertTrue(Superglobal::isGetKey('sString'));
        $this->assertTrue(Superglobal::isGetKey('sString2'));
        $this->assertTrue(Superglobal::isGetKey('bBool1'));
        $this->assertTrue(Superglobal::isGetKey('bBool2'));

        $this->assertFalse(Superglobal::isGetKey('ury8'));
        $this->assertFalse(Superglobal::isGetKey('bbdshuf'));
        $this->assertFalse(Superglobal::isGetKey('bbool'));
        $this->assertFalse(Superglobal::isGetKey(''));
    }
    
    public function testGetGetValueByKey()
    {
        $_GET['iTest1'] = 0;
        $_GET['iTest2'] = 15548525464643;
        $_GET['sString1'] = '';
        $_GET['sString2'] = 'fdkgfdgjfdfgdfhghg';
        $_GET['bBool1'] = true;
        $_GET['bBool2'] = false;
        $_GET['uValue'] = false;
        
        $this->assertEquals(Superglobal::getGetValueByKey('iTest1'), $_GET['iTest1']);
        $this->assertEquals(Superglobal::getGetValueByKey('iTest2'), $_GET['iTest2']);
        
        $this->assertEquals(Superglobal::getGetValueByKey('sString1'), $_GET['sString1']);
        $this->assertEquals(Superglobal::getGetValueByKey('sString2'), $_GET['sString2']);
        
        $this->assertEquals(Superglobal::getGetValueByKey('bBool1'), $_GET['bBool1']);
        $this->assertEquals(Superglobal::getGetValueByKey('bBool2'), $_GET['bBool2']);
        
        $this->assertEquals(Superglobal::getGetValueByKey('uValue'), $_GET['uValue']);
    }
    
        public function testIsPostKey()
    {
        $_POST['iTest'] = 4654;
        $_POST['sString'] = 'nhg,nxgbfvdshjhghfdghg';
        $_POST['sString2'] = '';
        $_POST['bBool1'] = true;
        $_POST['bBool2'] = false;

        $this->assertTrue(Superglobal::isPostKey('iTest'));
        $this->assertTrue(Superglobal::isPostKey('sString'));
        $this->assertTrue(Superglobal::isPostKey('sString2'));
        $this->assertTrue(Superglobal::isPostKey('bBool1'));
        $this->assertTrue(Superglobal::isPostKey('bBool2'));

        $this->assertFalse(Superglobal::isPostKey('ury8'));
        $this->assertFalse(Superglobal::isPostKey('bbdshuf'));
        $this->assertFalse(Superglobal::isPostKey('bbool'));
        $this->assertFalse(Superglobal::isPostKey(''));
    }
    
    public function testGetPostValueByKey()
    {
        $_POST['iTest1'] = 0;
        $_POST['iTest2'] = 847397564757948754;
        $_POST['sString1'] = '';
        $_POST['sString2'] = 'hgfghgdfhkhgbvbcwdfgfv';
        $_POST['bBool1'] = true;
        $_POST['bBool2'] = false;
        $_POST['uValue'] = false;
        
        $this->assertEquals(Superglobal::getPostValueByKey('iTest1'), $_POST['iTest1']);
        $this->assertEquals(Superglobal::getPostValueByKey('iTest2'), $_POST['iTest2']);
        
        $this->assertEquals(Superglobal::getPostValueByKey('sString1'), $_POST['sString1']);
        $this->assertEquals(Superglobal::getPostValueByKey('sString2'), $_POST['sString2']);
        
        $this->assertEquals(Superglobal::getPostValueByKey('bBool1'), $_POST['bBool1']);
        $this->assertEquals(Superglobal::getPostValueByKey('bBool2'), $_POST['bBool2']);
        
        $this->assertEquals(Superglobal::getPostValueByKey('uValue'), $_POST['uValue']);
    }
}
