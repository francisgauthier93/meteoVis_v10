<?php

/**
 * Description of WarningFactory
 *
 * @author molinspa
 */
class WarningFactory
{
    public static function fromMeteoCodeXml($oMeteoCodeXmlWarning)
    {
        $oWarning = null;
        
        if(!empty($oMeteoCodeXmlWarning))
        {
            $sStartDate = Date::getFullDateFromIso8601((string)$oMeteoCodeXmlWarning->attributes()->start);
            $sEndDate = Date::getFullDateFromIso8601((string)$oMeteoCodeXmlWarning->attributes()->end);
            $sRawType = (string)$oMeteoCodeXmlWarning->attributes()->type;
            $sType = ($sRawType !== 'nil') ? $sRawType : null;
            $sRawCode = (string)$oMeteoCodeXmlWarning->attributes()->code;
            $sCode = ($sRawCode !== 'nil') ? $sRawCode : null;
            $sRawStatus = (string)$oMeteoCodeXmlWarning->attributes()->status;
            $sStatus = ($sRawStatus !== 'nil') ? $sRawStatus : null;
            $sDescription = (string)$oMeteoCodeXmlWarning;
            
            $oWarning = new Warning($sStartDate, $sEndDate, $sCode, $sType, 
                    $sDescription, $sStatus);
        }
        
        return $oWarning;
    }
    
    public static function fromDatabase($sStartDate, $sEndDate, $sCode, 
            $sType, $sDescription, $sState)
    {
        $oWarning = new Warning($sStartDate, $sEndDate, 
                $sCode, $sType, $sDescription, $sState);
        
        return $oWarning;
    }
}