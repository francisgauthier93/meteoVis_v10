<?php

/**
 * Description of UpdateFactory
 *
 * @author molinspa
 */
class UpdateFactory
{
    public static function fromMeteoCodeXml($sUpdateUrl, $oMeteoCodeXmlInfo)
    {
        $oUpdate = null;
        if(!empty($oMeteoCodeXmlInfo))
        {
            $sType = (string) $oMeteoCodeXmlInfo->{'type'};
            $sValidStartDate = Date::getFullDateFromIso8601((string) $oMeteoCodeXmlInfo->{'valid-begin-time'});
            $sValidEndDate = Date::getFullDateFromIso8601((string) $oMeteoCodeXmlInfo->{'valid-end-time'});
            $sCreationDate = Date::getFullDateFromIso8601((string) $oMeteoCodeXmlInfo->{'creation-date'});
            
            $oUpdate = new Update(null, $sType, 
                    $sValidStartDate, $sValidEndDate, $sCreationDate, $sUpdateUrl);
        }
        
        return $oUpdate;
    }
    
    public static function fromDatabase($iId, $sType, $sValidStartDate, 
                $sValidEndDate, $sCreationDate, $sUrl)
    {
        $oUpdate = new Update(Number::getInt($iId), $sType, $sValidStartDate, 
                $sValidEndDate, $sCreationDate, $sUrl);
        
        return $oUpdate;
    }
}
