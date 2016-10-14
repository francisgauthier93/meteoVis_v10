<?php

/**
 * Description of Timer
 *
 * @author molinspa
 */
class Timer
{
    private static $iStartTime;
    
    public static function init()
    {
        self::$iStartTime = microtime(true);
    }
    
    public static function reset()
    {
        self::$iStartTime = microtime(true);
    }
    
    public static function getSeconds()
    {
        return Number::getRoundedFloat((microtime(true) - self::$iStartTime), 0);
    }
}