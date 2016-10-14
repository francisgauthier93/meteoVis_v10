<?php

/**
 * Description of BaseJsonView
 *
 * @author molinspa
 */
abstract class BaseJsonView
{
    protected $sOutput;
    
    public function setOutput(array $aOutput = array())
    {
        $this->sOutput = Conversion::getJsonFromArray($aOutput);
    }
    
    public function getOutput()
    {
        return $this->sOutput;
    }
}