<?php

/**
 * Description of DownloadException
 *
 * @author molinspa
 */
class DownloadException extends BaseException implements ExceptionInterface
{
    public function __construct($sDebugMessage = null, Exception $oPrevious = null)
    {
        parent::__construct(Config::get('exception.download.message.fr'), $sDebugMessage,  
                Config::get('exception.download.code'), $oPrevious);
    }
}