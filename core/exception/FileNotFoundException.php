<?php

/**
 * Description of FileNotFoundException
 *
 * @author molinspa
 */
class FileNotFoundException extends BaseException implements ExceptionInterface
{
    public function __construct($sDebugMessage = null, Exception $oPrevious = null)
    {
        parent::__construct(Config::get('exception.file_not_found.message.fr'), $sDebugMessage,  
                Config::get('exception.file_not_found.code'), $oPrevious);
    }
}