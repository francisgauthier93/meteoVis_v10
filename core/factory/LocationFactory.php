<?php

/**
 * Description of LocationFactory
 *
 * @author molinspa
 */
class LocationFactory
{
    public static function fromMeteoCodeXml($oMeteoCodeXmlInfo, $sRegionAbbreviation, 
            $oMeteoCodeXmlLocation)
    {
        $aMatchList = array();
        $sRawTimeZone = (string) $oMeteoCodeXmlInfo->{'timezone'};
        preg_match_all( '/[^0-9]+/', $sRawTimeZone, $aMatchList );
        $sTimeZoneFr = (isset($aMatchList[0]) && isset($aMatchList[0][0])) ? strtoupper((string)$aMatchList[0][0]) : null;
        $sTimeZoneEn = (isset($aMatchList[0]) && isset($aMatchList[0][1])) ? strtoupper((string)$aMatchList[0][1]) : null;
                
        $sCode = (string)$oMeteoCodeXmlLocation->{'msc-zone-code'};
        
        $aXmlNameList = $oMeteoCodeXmlLocation->{'msc-zone-name'};
        
        $sNameFr = null; $sNameEn = null;
        foreach($aXmlNameList as $aXmlName)
        {
            if((string)$aXmlName['lang'] === 'fr')
            {
                $sNameFr = (string)$aXmlName;
            }
            else if((string)$aXmlName['lang'] === 'en')
            {
                $sNameEn = (string)$aXmlName;
            }
        }
        
        $fLatitude = null; $fLongitude = null;
        if(isset($oMeteoCodeXmlLocation->point)
                && isset($oMeteoCodeXmlLocation->point->latitude)
                && isset($oMeteoCodeXmlLocation->point->longitude))
        {
            $fLatitude = Number::getFloat((string)$oMeteoCodeXmlLocation->point->latitude);
            $fLongitude = Number::getFloat((string)$oMeteoCodeXmlLocation->point->longitude);
        }
        
        $oLocation = new Location(null, $sCode, $sNameFr, $sNameEn, $fLatitude, 
                $fLongitude, $sRegionAbbreviation, $sTimeZoneFr, $sTimeZoneEn);
        
        return $oLocation;
    }
    
    public static function fromDatabase($iId, $sCode, $sNameFr, $sNameEn, 
            $fLatitude, $fLongitude, $sRegionAbbreviation, 
            $sTimeZoneFr, $sTimeZoneEn)
    {
        $oLocation = new Location(Number::getInt($iId), $sCode, $sNameFr, $sNameEn, 
                Number::getFloat($fLatitude), Number::getFloat($fLongitude), 
                $sRegionAbbreviation, $sTimeZoneFr, $sTimeZoneEn);
        
        return $oLocation;
    }
}
