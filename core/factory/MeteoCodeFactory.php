<?php

/**
 * Description of MeteoCodeFactory
 *
 * @author molinspa
 */
class MeteoCodeFactory
{
    public static function fromObject(Location $oLocation, 
            Update $oLastUpdate, 
            array $aAirTemperatureList, array $aWindList,
            array $aAccumulationList, array $aCloudCoverList, 
            array $aPrecipitationList, array $aWarningList)
    {
        $oMeteoCode = new MeteoCode($oLocation, $oLastUpdate,
                $aAirTemperatureList, $aWindList,
                $aAccumulationList, $aCloudCoverList, 
                $aPrecipitationList, $aWarningList);
        
        return $oMeteoCode;
    }
}