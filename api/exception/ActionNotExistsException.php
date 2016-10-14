<?php

/**
 * Description of ActionNotExistsException
 *
 * @author molinspa
 */
class ActionNotExistsException extends BaseException implements ExceptionInterface
{
    public function __construct($sDebugMessage = null, Exception $oPrevious = null)
    {
        parent::__construct(Config::get('exception.action_not_exists.message.fr'), $sDebugMessage,  
                Config::get('exception.action_not_exists.code'), $oPrevious);
    }
}