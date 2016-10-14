<?php

/**
 * Description of Warning
 *
 * @author molinspa
 */
class Warning
{
    private $sStartDate;
    private $sEndDate;
    private $sCode;
    private $sType;
    private $sDescription;
    private $sState;
    
    function __construct($sStartDate, $sEndDate, $sCode, $sType, 
            $sDescription, $sState)
    {
        $this->sStartDate = $sStartDate;
        $this->sEndDate = $sEndDate;
        $this->sCode = $sCode;
        $this->sType = $sType;
        $this->sDescription = $sDescription;
        $this->sState = $sState;
    }

    function getStartDate()
    {
        return $this->sStartDate;
    }

    function getEndDate()
    {
        return $this->sEndDate;
    }

    function getCode()
    {
        return $this->sCode;
    }

    function getType()
    {
        return $this->sType;
    }

    function getDescription()
    {
        return $this->sDescription;
    }

    function getState()
    {
        return $this->sState;
    }
}
