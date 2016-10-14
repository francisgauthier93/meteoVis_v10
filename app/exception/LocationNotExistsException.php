<?php

/**
 * Description of LocationNotExistsException
 *
 * @author molinspa
 */
class LocationNotExistsException extends BaseException implements ExceptionInterface
{
    public function __construct($sDebugMessage = null, Exception $oPrevious = null)
    {
        parent::__construct(Config::get('exception.location_not_exists.message.fr'), $sDebugMessage,  
                Config::get('exception.location_not_exists.code'), $oPrevious);
    }
}