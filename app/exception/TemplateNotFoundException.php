<?php

/**
 * Description of TemplateNotFoundException
 *
 * @author molinspa
 */
class TemplateNotFoundException extends BaseException implements ExceptionInterface
{
    public function __construct($sDebugMessage = null, Exception $oPrevious = null)
    {
        parent::__construct(Config::get('exception.tpl_not_found.message.fr'), $sDebugMessage,  
                Config::get('exception.tpl_not_found.code'), $oPrevious);
    }
}