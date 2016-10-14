<?php

ob_start();

header('Content-Type: text/html; charset=utf-8');

/*** error reporting on ***/
error_reporting(-1);
ini_set('display_errors', 'On');

define('REAL_PATH_ROOT', realpath('../') . '/');
require_once REAL_PATH_ROOT . 'autoloader.php';

try
{
    if(Superglobal::isGetKey('sAction'))
    {
        $sAction = Superglobal::getGetValueByKey('sAction');

        $aControllerList = array(
            'create_jsreal_resource' => 'CreateJSRealResourceController'
        );

        if(isset($aControllerList[$sAction]))
        {
            $oController = new $aControllerList[$sAction]();

            $sOutput = $oController->getView()->getOutput();
        }
        else
        {
            throw new ActionNotExistsException('Action name = ' . $sAction);
        }
    }
    else
    {
        throw new WrongParameterException('Issue with parameter sAction');
    }
}
catch(Exception $e)
{
    $sContent = ob_get_contents();
    ob_end_clean();
    
    if(Config::isDevEnv())
    {
        $sOutput = 'Erreur : ' . $e->getMessage() . '<br />';
        $sOutput .= 'Debug : ' . $e->getDebugMessage() . '<br />';
        $sOutput .= $sContent . '<br />';
        $sOutput .= 'Trace : ' . $e->getTraceAsString();
        Util::var_dump($e->getTrace());
    }
    else
    {
        $sOutput = Conversion::getJsonFromArray(array('bSuccess' => false, 
            'sErrorMessage' => $e->getMessage(), 'iErrorCode' => $e->getCode()));
    }
}

//ob_end_clean();

echo $sOutput;