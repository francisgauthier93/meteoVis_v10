<?php

/**
 * Description of Conversion
 *
 * @author molinspa
 */
class Conversion
{
    public static function getJsonFromArray(array $aTarget, $bCompress = false)
    {
        // PHP 5.4
        $sOptions = ($bCompress) ? JSON_UNESCAPED_UNICODE : (JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        $sJsonContent = json_encode($aTarget, $sOptions);
        
        // PHP 5.3
//        $sJsonContent = json_encode($aTarget, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
        
        if(json_last_error() === JSON_ERROR_NONE 
//                && $sJsonContent !== false && !is_null($sJsonContent) && !empty($sJsonContent)
                )
        {
            return $sJsonContent;
        }
        else
        {
            throw new InvalidJsonException('Try to convert array to json');
        }
    }
    
    public static function getArrayFromJson($sJson)
    {
        $aArray = json_decode($sJson, true);

        if(json_last_error() === JSON_ERROR_NONE 
//                && $aArray !== false && !is_null($aArray) && is_array($aArray)
                )
        {
            return $aArray;
        }
        else
        {
            throw new InvalidJsonException('Try to convert json to array');
        }
    }
}