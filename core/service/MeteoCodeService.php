<?php

/**
 * Description of MeteocodeService
 *
 * @author molinspa
 */
class MeteocodeService
{
    public function __construct()
    {
        ;
    }
    
    public function GetMeteocodeFromLocationCode($sLocationCode)
    {
        $oMeteoCodeDao = new MeteoCodeDao(true, array('sLocationCode' => $sLocationCode));
        $oMeteoCode = $oMeteoCodeDao->getFirstElement();
        unset($oMeteoCodeDao);
        
        return $oMeteoCode;
    }
}