<?php

/**
 * Description of Graphic
 *
 * @author molinspa
 */
class Graphic
{
    public static function rectangle($aAttributeList, $sColor = 'black')
    {
        $sAttribute = self::getAttributeFromArray($aAttributeList);
        
        return '<rect' . $sAttribute . ' style="fill:' . $sColor . ';stroke-width:0.3;" />';
    }
    
    public static function text($sText, $aAttributeList)
    {
        $sAttribute = self::getAttributeFromArray($aAttributeList);
        
        return '<text' . $sAttribute . '>' . $sText . '</text>';
    }
    
    public static function useAnimated($aUseAttributeList, $aAnimateAttributeList)
    {
        $sReturnString = '';
        
        $sUseAttribute = self::getAttributeFromArray($aUseAttributeList);
        $sAnimatedAttribute = self::getAttributeFromArray($aAnimateAttributeList);
//        $sAnimatedAttribute = '';
        
        $sReturnString .= '<use ' . $sUseAttribute . '>';
        $sReturnString .= '<animate ' . $sAnimatedAttribute . ' />';
        $sReturnString .= '</use>';
        
        return $sReturnString;
    }
    
    public static function __callStatic($sMethodName, $aArgumentList)
    {
        $sAttribute = self::getAttributeFromArray(Arr::last($aArgumentList));
        
        if(strtolower($sMethodName) === 'usesvg')
        {
            return '<use' . $sAttribute . ' />';
        }
        else
        {
            return '<' . strtolower($sMethodName) . $sAttribute . ' />';
        }
    }
    
    private static function getAttributeFromArray(array $aArray)
    {
        $sAttribute = '';
        
        foreach($aArray as $sKey => $sValue)
        {
            $sAttribute .=  ' ' . $sKey . '="' . $sValue . '"';
        }
        
        return $sAttribute;
    }
}