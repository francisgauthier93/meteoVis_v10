<?php

/**
 * Description of PrecipitationFactory
 *
 * @author molinspa
 */
class PrecipitationFactory
{
    public static function fromMeteoCodeXml($aProbabilityList, $sProbabilityUnit, $oMeteoCodeXmlPrecipitation)
    {
        $oPrecipitation = null;
        
        if(!empty($oMeteoCodeXmlPrecipitation))
        {
            $sStartDate = Date::getFullDateFromIso8601((string)$oMeteoCodeXmlPrecipitation->attributes()->start);
            $sEndDate = Date::getFullDateFromIso8601((string)$oMeteoCodeXmlPrecipitation->attributes()->end);
            $sRawType = (string)$oMeteoCodeXmlPrecipitation->attributes()->type;
            $sType = (strtolower($sRawType) != 'nil') ?  strtolower($sRawType) : null;
            $sRawFrequency = (string)$oMeteoCodeXmlPrecipitation->attributes()->frequency;
            $sFrequency = ($sRawFrequency !== 'nil') ? strtolower($sRawFrequency) : null;
            $sRawIntensity = (string)$oMeteoCodeXmlPrecipitation->attributes()->intensity;
            $sIntensity = ($sRawIntensity !== 'nil') ? strtolower($sRawIntensity) : null;
            
            $i = 0;
            $fTmpProbability = 0;
            foreach($aProbabilityList as $aProbability)
            {
                if(($aProbability['sStartDate'] <= $sStartDate
                        && $aProbability['sEndDate'] >= $sEndDate)
                    || ($aProbability['sStartDate'] >= $sStartDate
                        && $aProbability['sEndDate'] <= $sEndDate))
                {
                    $fTmpProbability += $aProbability['fProbability'];
                    $i++;
                }
            }
            
            $fProbability = ($i > 0) ? Number::getFloat($fTmpProbability / $i) : null;
  
            if($sType !== null)
            {
                $oPrecipitation = new Precipitation($sStartDate, $sEndDate, $sType, 
                        $sFrequency, $sIntensity, $fProbability, $sProbabilityUnit);
            }
        }
        
        return $oPrecipitation;
    }
    
    public static function fromDatabase($sStartDate, $sEndDate, $sType, 
                $sFrequency, $sIntensity, $fProbability, $sProbabilityUnit)
    {
        $oPrecipitation = new Precipitation($sStartDate, $sEndDate, $sType, 
                $sFrequency, $sIntensity, Number::getFloat($fProbability), 
                $sProbabilityUnit);
        
        return $oPrecipitation;
    }
}
