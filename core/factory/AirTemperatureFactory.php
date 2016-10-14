<?php

/**
 * Description of AirTemperatureFactory
 *
 * @author molinspa
 */
class AirTemperatureFactory
{
    public static function fromMeteoCodeXml($sUnit, $oMeteoCodeXmlAirTemperature)
    {
        $oAirTemperature = null;
        
        if(!empty($oMeteoCodeXmlAirTemperature))
        {
            $sStartDate = Date::getFullDateFromIso8601((string)$oMeteoCodeXmlAirTemperature->attributes()->start);
            $sEndDate = Date::getFullDateFromIso8601((string)$oMeteoCodeXmlAirTemperature->attributes()->end);
            $fRawMinValue = Number::getFloat(
                    (string)$oMeteoCodeXmlAirTemperature->{'lower-limit'});
            $fMinValue = ($fRawMinValue <= Config::get('meteo.limit.temperature.min')
                            || $fRawMinValue >= Config::get('meteo.limit.temperature.max')) ? null : $fRawMinValue;
            $fRawMaxValue = Number::getFloat(
                    (string)$oMeteoCodeXmlAirTemperature->{'upper-limit'});
            $fMaxValue = ($fRawMaxValue <= Config::get('meteo.limit.temperature.min')
                            || $fRawMaxValue >= Config::get('meteo.limit.temperature.max')) ? null : $fRawMaxValue;

            if($fMinValue !== null || $fMaxValue !== null)
            {
                $oAirTemperature = new AirTemperature($sStartDate, 
                        $sEndDate, $fMinValue, $fMaxValue, $sUnit);
            }
        }
        
        return $oAirTemperature;
    }
    
    public static function fromDatabase($sStartDate, $sEndDate, 
            $fMinValue, $fMaxValue, $sUnit)
    {
        $oAirTemperature = new AirTemperature($sStartDate, $sEndDate, 
                Number::getFloat($fMinValue), Number::getFloat($fMaxValue), $sUnit);
        
        return $oAirTemperature;
    }
    
    public static function getMinimumAirTemperature(array $aAirTemperatureList)
    {
        $fMinimumTemperature = null;
        if(!empty($aAirTemperatureList))
        {
            $fMinimumTemperature = $aAirTemperatureList[0]->getAvgValue();
            foreach($aAirTemperatureList as $oTemperature)
            {
                if($oTemperature->getAvgValue() < $fMinimumTemperature)
                {
                    $fMinimumTemperature = $oTemperature->getAvgValue();
                }
            }
        }
        
        return $fMinimumTemperature;
    }
    
    public static function getAverageAirTemperature(array $aAirTemperatureList)
    {
        $fAverageTemperature = null;
        if(!empty($aAirTemperatureList))
        {
            $i = 0;
            $fTotal = 0;
            foreach($aAirTemperatureList as $oTemperature)
            {
                $fTotal += $oTemperature->getAvgValue();

                $i++;
            }
            $fAverageTemperature = $fTotal / Number::getFloat($i);
        }
        
        return $fAverageTemperature;
    }
    
    public static function getMaximumAirTemperature(array $aAirTemperatureList)
    {
        $fMaximumTemperature = null;
        if(!empty($aAirTemperatureList))
        {
            $fMaximumTemperature = $aAirTemperatureList[0]->getAvgValue();
            foreach($aAirTemperatureList as $oTemperature)
            {
                if($oTemperature->getAvgValue() > $fMaximumTemperature)
                {
                    $fMaximumTemperature = $oTemperature->getAvgValue();
                }
            }
        }
        
        return $fMaximumTemperature;
    }
}
