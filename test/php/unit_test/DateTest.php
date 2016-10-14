<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DateTest
 *
 * @author molinspa
 */
class DateTest  extends PHPUnit_Framework_TestCase
{
    public function testDayDiff()
    {
        $this->assertEquals(Date::getFullDateDayDiff('2015-02-01 00:00:00', '2015-02-03 00:00:00'), 2);
        $this->assertEquals(Date::getFullDateDayDiff('2015-02-01 00:00:00', '2015-02-01 23:00:00'), 0);
        $this->assertEquals(Date::getFullDateDayDiff('2015-01-31 00:00:00', '2015-02-01 00:00:00'), 1);
    }
    
    public function testHourDiff()
    {
        $this->assertEquals(Date::getFullDateHourDiff('2015-02-01 00:00:00', '2015-02-04 00:00:00'), 72);
        $this->assertEquals(Date::getFullDateHourDiff('2015-02-01 00:00:00', '2015-02-01 10:59:00'), 10);
        $this->assertEquals(Date::getFullDateHourDiff('2015-02-02 00:00:00', '2015-02-01 00:00:00'), 24);
        $this->assertEquals(Date::getFullDateHourDiff('2015-03-08 01:00:00', '2015-03-08 03:00:00'), 2);
        $this->assertEquals(Date::getFullDateHourDiff('2015-03-03 17:00:00', '2015-03-08 03:00:00'), 111);
    }
    
    public function testDaySetter()
    {
        $this->assertEquals(Date::setToStartOfDay('2015-02-01 12:40:45'), '2015-02-01 00:00:00');
        $this->assertEquals(Date::setToEndOfDay('2015-02-01 12:40:45'), '2015-02-01 23:59:59');
        
        $this->assertEquals(Date::setToNextHour('2015-02-01 23:40:45'), '2015-02-02 00:00:00');
        $this->assertEquals(Date::setToNextHour('2015-02-01 12:00:00'), '2015-02-01 13:00:00');
        $this->assertEquals(Date::setToNextHour('2015-02-01 12:42:00'), '2015-02-01 13:00:00');
    }
    
    public function testDayAdder()
    {
        $this->assertEquals(Date::addDay('2015-01-01 12:40:45', -1), '2014-12-31 12:40:45');
        $this->assertEquals(Date::addDay('2015-01-01 12:40:45', 1), '2015-01-02 12:40:45');
        $this->assertEquals(Date::addDay('2015-01-01 23:59:59', 0), '2015-01-01 23:59:59');
        $this->assertEquals(Date::addDay('2015-01-01 23:59:59', 32), '2015-02-02 23:59:59');
    }
    
    public function testHourAdder()
    {
        $this->assertEquals(Date::addHour('2015-01-01 00:00:00', -1), '2014-12-31 23:00:00');
        $this->assertEquals(Date::addHour('2015-01-01 12:40:45', 1), '2015-01-01 13:40:45');
        $this->assertEquals(Date::addHour('2015-01-01 23:59:59', 0), '2015-01-01 23:59:59');
        $this->assertEquals(Date::addHour('2015-01-01 23:59:59', 26), '2015-01-03 01:59:59');
    }
    
    public function testSecondAdder()
    {
        $this->assertEquals(Date::addSecond('2015-01-01 00:00:00', -1), '2014-12-31 23:59:59');
        $this->assertEquals(Date::addSecond('2015-01-01 23:59:59', 1), '2015-01-02 00:00:00');
        $this->assertEquals(Date::addSecond('2015-01-01 23:59:59', 0), '2015-01-01 23:59:59');
        $this->assertEquals(Date::addSecond('2015-01-01 23:00:59', 62), '2015-01-01 23:02:01');
    }
}
