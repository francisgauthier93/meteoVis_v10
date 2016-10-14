<?php

/**
 * Description of Import
 */
class Import
{
    /**
     * Return Html code for import css
     * @param type $sRootPath Path from root
     * @return string
     */
    public static function css($sRootPath)
    {
        return '<link rel="stylesheet" href="' . Http::getStaticProperUrl($sRootPath) . '" type="text/css" charset="utf-8" />';
    }
    
    /**
     * Return Html code for import js
     * @param type $sRootPath Path from root
     * @return string
     */
    public static function js($sRootPath)
    {
        return '<script type="text/javascript" charset="utf-8" src="' . Http::getStaticProperUrl($sRootPath) . '"></script>';
    }
}