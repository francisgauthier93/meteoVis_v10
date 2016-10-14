<?php

/**
 * Description of WindFactory
 *
 * @author molinspa
 */
class WindFactory
{
    public static function fromMeteoCodeXml($sSpeedUnit, $oMeteoCodeXmlWind)
    {
        $oWind = null;
        
        if(!empty($oMeteoCodeXmlWind))
        {
            $sStartDate = Date::getFullDateFromIso8601((string)$oMeteoCodeXmlWind->attributes()->start);
            $sEndDate = Date::getFullDateFromIso8601((string)$oMeteoCodeXmlWind->attributes()->end);
            $sRawDirection = (string)$oMeteoCodeXmlWind->attributes()->direction;
            $sDirection = ($sRawDirection !== 'nil') ? $sRawDirection : null;
            $fMinSpeed = Number::getFloat((string)$oMeteoCodeXmlWind->{'wind-speed'}->{'lower-limit'});
            $fMaxSpeed = Number::getFloat((string)$oMeteoCodeXmlWind->{'wind-speed'}->{'upper-limit'});
            
            $oWind = new Wind($sStartDate, $sEndDate, $fMinSpeed, 
                    $fMaxSpeed, $sSpeedUnit, $sDirection);
        }
        
        return $oWind;
    }
    
    public static function fromDatabase($sStartDate, $sEndDate, $fMinSpeed, 
            $fMaxSpeed, $sSpeedUnit, $sDirection)
    {
        $oWind = new Wind($sStartDate, $sEndDate, 
                Number::getFloat($fMinSpeed), Number::getFloat($fMaxSpeed), 
                $sSpeedUnit, $sDirection);
        
        return $oWind;
    }
}