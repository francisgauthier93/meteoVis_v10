<?php

/**
 * Description of MeteoCode
 *
 * @author molinspa
 */
class MeteoCode
{
    private $oLocation;
    private $oLastUpdate;
    private $aAirTemperatureList;
    private $aWindList;
    private $aAccumulationList;
    private $aCloudCoverList;
    private $aPrecipitationList;
    private $aWarningList;
//    private $aDayList;

    public function __construct(Location $oLocation, 
            Update $oLastUpdate, 
            array $aAirTemperatureList, array $aWindList,
            array $aAccumulationList, array $aCloudCoverList, 
            array $aPrecipitationList, array $aWarningList)
    {
        $this->oLocation = $oLocation;
        $this->oLastUpdate = $oLastUpdate;
        $this->aAirTemperatureList = $aAirTemperatureList;
        $this->aWindList = $aWindList;
        $this->aAccumulationList = $aAccumulationList;
        $this->aCloudCoverList = $aCloudCoverList;
        $this->aPrecipitationList = $aPrecipitationList;
        $this->aWarningList = $aWarningList;
    }

    public function getLocation()
    {
        return $this->oLocation;
    }
    
    public function setLocation(Location $oLocation)
    {
        $this->oLocation = $oLocation;
    }

    public function getLastUpdate()
    {
        return $this->oLastUpdate;
    }

    public function getAirTemperatureList()
    {
        return $this->aAirTemperatureList;
    }

    public function getWindList()
    {
        return $this->aWindList;
    }

    public function getAccumulationList()
    {
        return $this->aAccumulationList;
    }

    public function getCloudCoverList()
    {
        return $this->aCloudCoverList;
    }

    public function getPrecipitationList()
    {
        return $this->aPrecipitationList;
    }

    public function getWarningList()
    {
        return $this->aWarningList;
    }
}