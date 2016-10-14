<?php

/**
 * Description of AirTemperature
 *
 * @author molinspa
 */
class AirTemperature
{
    private $sStartDate;
    private $sEndDate;
    private $fMinValue;
    private $fMaxValue;
    private $sUnit;
    
    public function __construct($sStartDate, $sEndDate, $fMinValue, $fMaxValue, $sUnit)
    {
        $this->sStartDate = $sStartDate;
        $this->sEndDate = $sEndDate;
        $this->fMinValue = $fMinValue;
        $this->fMaxValue = $fMaxValue;
        $this->sUnit = $sUnit;
    }

    public function getStartDate()
    {
        return $this->sStartDate;
    }

    public function getEndDate()
    {
        return $this->sEndDate;
    }

    public function getMinValue()
    {
        return $this->fMinValue;
    }

    public function getMaxValue()
    {
        return $this->fMaxValue;
    }
    
    public function getAvgValue()
    {
        if(is_null($this->fMinValue))
        {
            return $this->fMaxValue;
        }
        else if(is_null($this->fMaxValue))
        {
            return $this->fMinValue;
        }
        else
        {
            return Number::getFloat(($this->fMinValue + $this->fMaxValue) / 2);
        }
    }

    public function getUnit()
    {
        return $this->sUnit;
    }
}
