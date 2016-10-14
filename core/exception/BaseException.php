<?php

/**
 * 
 */
class BaseException extends Exception
{
    private $sPublicMessage;
    private $sDebugMessage;
    
    public function __construct($sPublicMessage = null, $sDebugMessage = null, 
            $iCode = 0, Exception $oPrevious = null)
    {
        parent::__construct($sPublicMessage, $iCode, $oPrevious);
        
        $this->sPublicMessage = $sPublicMessage;
        $this->sDebugMessage = $sDebugMessage;
    }
    
    public function getPublicMessage()
    {
        return $this->sPublicMessage;
    }
    
    public function getDebugMessage()
    {
        return $this->sDebugMessage;
    }
}