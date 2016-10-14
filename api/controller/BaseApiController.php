<?php

/**
 * Description of BaseApiController
 *
 * @author molinspa
 */
abstract class BaseApiController
{
    abstract public function execute();
    
    public function getView()
    {
        return $this->execute();
    }
}