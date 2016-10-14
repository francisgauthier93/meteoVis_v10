<?php

/**
 * Description of ErrorJsonView
 *
 * @author molinspa
 */
class ErrorJsonView extends BaseJsonView
{
    public function __construct(Exception $oException)
    {        
        $aOutput = array(
                'bSuccess' => false,
                'sErrorMessage' => $oException->getMessage(),
                'iErrorCode' => $oException->getCode()
            );
        
        $this->setOutput($aOutput);
    }
}