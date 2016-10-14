<?php

/**
 * Description of CloudCoverFactory
 *
 * @author molinspa
 */
class CloudCoverFactory
{
    public static function fromMeteoCodeXml($sUnit, $oMeteoCodeXmlCloud)
    {
        $oCloudCover = null;
        
        if(!empty($oMeteoCodeXmlCloud))
        {
            $iValue = Number::getInt((string)$oMeteoCodeXmlCloud);
            $sStartDate = Date::getFullDateFromIso8601((string)$oMeteoCodeXmlCloud->attributes()->start);
            $sEndDate = Date::getFullDateFromIso8601((string)$oMeteoCodeXmlCloud->attributes()->end);

            $oCloudCover = new CloudCover($sStartDate, $sEndDate, $iValue, $sUnit);
        }
        
        return $oCloudCover;
    }
    
    public static function fromDatabase($sStartDate, $sEndDate, $iValue, $sUnit)
    {
        $oCloudCover = new CloudCover($sStartDate, $sEndDate, Number::getInt($iValue), $sUnit);
        
        return $oCloudCover;
    }
}
