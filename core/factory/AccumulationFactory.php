<?php

/**
 * Description of AccumulationFactory
 *
 * @author molinspa
 */
class AccumulationFactory
{
    public static function fromMeteoCodeXml($sUnit, $oMeteoCodeXmlAccumulation)
    {
        $oAccumulation = null;
        
        if(!empty($oMeteoCodeXmlAccumulation))
        {
            $sStartDate = Date::getFullDateFromIso8601((string)$oMeteoCodeXmlAccumulation->attributes()->start);
            $sEndDate = Date::getFullDateFromIso8601((string)$oMeteoCodeXmlAccumulation->attributes()->end);
            $sRawType = (string)$oMeteoCodeXmlAccumulation->attributes()->type;
            $sType = ($sRawType !== 'N/A') ? $sRawType : null;
            $fMinimumAmount = Number::getFloat(
                    (string)$oMeteoCodeXmlAccumulation->{'lower-limit'});
            $fMaximumAmount = Number::getFloat(
                    (string)$oMeteoCodeXmlAccumulation->{'upper-limit'});
            $fMinimumTotal = Number::getFloat(
                    (string)$oMeteoCodeXmlAccumulation->{'lower-limit'}
                            ->attributes()->{'cumul-inf'});
            $fMaximumTotal = Number::getFloat(
                            (string)$oMeteoCodeXmlAccumulation->{'upper-limit'}
                                    ->attributes()->{'cumul-sup'});
            
            if($sType !== null)
            {
                $oAccumulation = new Accumulation($sStartDate, $sEndDate, $fMinimumAmount, 
                    $fMaximumAmount, $fMinimumTotal, $fMaximumTotal, $sType, $sUnit);
            }
        }
        
        return $oAccumulation;
    }
    
    public static function fromDatabase($sStartDate, $sEndDate, $fMinimumAmount, $fMaximumAmount,
            $fMinimumTotal, $fMaximumTotal, $sType, $sUnit = null)
    {
        $oAccumulation = new Accumulation($sStartDate, $sEndDate, 
                Number::getFloat($fMinimumAmount), Number::getFloat($fMaximumAmount), 
                Number::getFloat($fMinimumTotal), Number::getFloat($fMaximumTotal), 
                $sType, $sUnit);
        
        return $oAccumulation;
    }
}
