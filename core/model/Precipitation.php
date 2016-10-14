<?php

/**
 * Description of Precipitation
 *
 * @author molinspa
 */
class Precipitation
{
    private $sStartDate;
    private $sEndDate;
    private $sType;
    private $sFrequency;
    private $sIntensity;
    private $fProbability;
    private $sProbabilityUnit;
    
    public function __construct($sStartDate, $sEndDate, $sType, $sFrequency,
            $sIntensity, $fProbability, $sProbabilityUnit)
    {
        $this->sStartDate = $sStartDate;
        $this->sEndDate = $sEndDate;
        $this->sType = $sType;
        $this->sFrequency = $sFrequency;
        $this->sIntensity = $sIntensity;
        $this->fProbability = $fProbability;
        $this->sProbabilityUnit = $sProbabilityUnit;
    }

    public function getStartDate()
    {
        return $this->sStartDate;
    }

    public function getEndDate()
    {
        return $this->sEndDate;
    }

    public function getType()
    {
        return $this->sType;
    }

    public function getFrequency()
    {
        return $this->sFrequency;
    }

    public function getIntensity()
    {
        return $this->sIntensity;
    }

    public function getProbability()
    {
        return $this->fProbability;
    }

    public function getProbabilityUnit()
    {
        return $this->sProbabilityUnit;
    }
}
