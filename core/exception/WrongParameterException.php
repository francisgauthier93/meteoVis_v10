<?php

/**
 * Description of WrongParameterException
 *
 * @author molinspa
 */
class WrongParameterException extends BaseException implements ExceptionInterface
{
    public function __construct($sDebugMessage = null, Exception $oPrevious = null)
    {
        parent::__construct(Config::get('exception.wrong_parameter.message.fr'), $sDebugMessage,  
                Config::get('exception.wrong_parameter.code'), $oPrevious);
    }
}