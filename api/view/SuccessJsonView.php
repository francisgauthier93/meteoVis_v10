<?php

/**
 * Description of SuccessJsonView
 *
 * @author molinspa
 */
class SuccessJsonView extends BaseJsonView
{
    public function __construct(array $aOutput = array())
    {
        $aTmpOutput = array_merge(array('bSuccess' => true), $aOutput);
        
        $this->setOutput($aTmpOutput);
    }
}