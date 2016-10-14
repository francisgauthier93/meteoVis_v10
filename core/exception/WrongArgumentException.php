<?php

/**
 * Description of WrongArgumentException
 *
 * @author molinspa
 */
class WrongArgumentException extends BaseException implements ExceptionInterface
{
    public function __construct($sDebugMessage = null, Exception $oPrevious = null)
    {
        parent::__construct(Config::get('exception.wrong_argument.message.fr'), $sDebugMessage,  
                Config::get('exception.wrong_argument.code'), $oPrevious);
    }
}