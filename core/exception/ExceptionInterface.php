<?php

/*
 * 
 */
interface ExceptionInterface
{
    public function __construct($sDebugMessage = null, Exception $oPrevious = null);
}