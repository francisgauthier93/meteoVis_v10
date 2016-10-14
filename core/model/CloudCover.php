<?php

/**
 * Description of CloudCover
 *
 * @author molinspa
 */
class CloudCover
{
    private $sStartDate;
    private $sEndDate;
    private $iValue;
    private $sUnit;
    
    function __construct($sStartDate, $sEndDate, $iValue, $sUnit)
    {
        $this->sStartDate = $sStartDate;
        $this->sEndDate = $sEndDate;
        $this->iValue = $iValue;
        $this->sUnit = $sUnit;
    }

    function getStartDate()
    {
        return $this->sStartDate;
    }

    function getEndDate()
    {
        return $this->sEndDate;
    }

    function getValue()
    {
        return $this->iValue;
    }

    function getUnit()
    {
        return $this->sUnit;
    }
}
