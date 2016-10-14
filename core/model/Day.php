<?php

/**
 * Description of Day
 *
 * @author molinspa
 */
class Day
{
    private $iNumber;
    private $sLabel;
    private $fMinimumTemperature;
    private $fMaximumTemperature;
    private $aTemperatureList;
    private $aWindList;
    private $aAccumulationList;
    private $aCloudCoverList;
    private $sDate;
    
    public function __construct($iNumber, $sLabel, $fMinimumTemperature,
            $fMaximumTemperature, $aTemperatureList, $aWindList, 
            $aAccumulationList, $aCloudCoverList, $sDate)
    {
        $this->iNumber = $iNumber;
        $this->sLabel = $sLabel;
        $this->fMinimumTemperature = $fMinimumTemperature;
        $this->fMaximumTemperature = $fMaximumTemperature;
        $this->aTemperatureList = $aTemperatureList;
        $this->aWindList = $aWindList;
        $this->aAccumulationList = $aAccumulationList;
        $this->aCloudCoverList = $aCloudCoverList;
        $this->sDate = $sDate;
    }

    public function getNumber()
    {
        return $this->iNumber;
    }

    public function getLabel()
    {
        return $this->sLabel;
    }

    public function getMinimumTemperature()
    {
        return $this->fMinimumTemperature;
    }

    public function getMaximumTemperature()
    {
        return $this->fMaximumTemperature;
    }

    public function getTemperatureList()
    {
        return $this->aTemperatureList;
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

    public function getDate()
    {
        return $this->sDate;
    }
}
