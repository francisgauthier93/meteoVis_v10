<?php

/**
 * Description of FileNotCreatedException
 *
 * @author molinspa
 */
class FileNotCreatedException extends BaseException implements ExceptionInterface
{
    public function __construct($sDebugMessage = null, Exception $oPrevious = null)
    {
        parent::__construct(Config::get('exception.file_not_created.message.fr'), $sDebugMessage,  
                Config::get('exception.file_not_created.code'), $oPrevious);
    }
}