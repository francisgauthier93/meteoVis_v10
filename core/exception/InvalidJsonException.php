<?php

/**
 * Description of InvalidJsonException
 *
 * @author molinspa
 */
class InvalidJsonException extends BaseException implements ExceptionInterface
{
    public function __construct($sDebugMessage = null, Exception $oPrevious = null)
    {
        parent::__construct(Config::get('exception.invalid_json.message.fr'), $sDebugMessage,  
                Config::get('exception.invalid_json.code'), $oPrevious);
    }
}