<?php

/**
 * Description of InvalidXmlException
 *
 * @author molinspa
 */
class InvalidXmlException extends BaseException implements ExceptionInterface
{
    public function __construct($sDebugMessage = null, Exception $oPrevious = null)
    {
        parent::__construct(Config::get('exception.invalid_xml.message.fr'), $sDebugMessage,  
                Config::get('exception.invalid_xml.code'), $oPrevious);
    }
}