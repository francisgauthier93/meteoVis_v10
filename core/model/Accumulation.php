<?php

/**
 * Description of Accumulation
 *
 * @author molinspa
 */
class Accumulation
{
    private $sStartDate;
    private $sEndDate;
    private $fMinimumAmount;
    private $fMaximumAmount;
    private $fMinimumTotal;
    private $fMaximumTotal;
    private $sType;
    private $sUnit;
    
    public function __construct($sStartDate, $sEndDate, $fMinimumAmount, $fMaximumAmount,
            $fMinimumTotal, $fMaximumTotal, $sType, $sUnit = null)
    {
        $this->sStartDate = $sStartDate;
        $this->sEndDate = $sEndDate;
        $this->fMinimumAmount = $fMinimumAmount;
        $this->fMaximumAmount = $fMaximumAmount;
        $this->fMinimumTotal = $fMinimumTotal;
        $this->fMaximumTotal = $fMaximumTotal;
        $this->sType = $sType;
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

    public function getMinimumAmount()
    {
        return $this->fMinimumAmount;
    }

    public function getMaximumAmount()
    {
        return $this->fMaximumAmount;
    }

    public function getMinimumTotal()
    {
        return $this->fMinimumTotal;
    }

    public function getMaximumTotal()
    {
        return $this->fMaximumTotal;
    }

    public function getType()
    {
        return $this->sType;
    }
    
    public function getUnit()
    {
        return $this->sUnit;
    }
}
