<?php

/**
 * Description of LanguageNotSupportedException
 *
 * @author molinspa
 */
class LanguageNotSupportedException extends BaseException implements ExceptionInterface
{
    public function __construct($sDebugMessage = null, Exception $oPrevious = null)
    {
        parent::__construct(Config::get('exception.language_not_supported.message.fr'), $sDebugMessage,  
                Config::get('exception.language_not_supported.code'), $oPrevious);
    }
}