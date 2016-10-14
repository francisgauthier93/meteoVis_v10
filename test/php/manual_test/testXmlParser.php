<?php

header('Content-Type: text/html; charset=UTF-8');

ob_start();

// Display errors
error_reporting(-1);
ini_set('display_errors', 'On');

define('REAL_PATH_ROOT', realpath('../../../') . '/');

require_once REAL_PATH_ROOT . 'autoloader.php';

try
{
    $sFileUrl = '../file/TRANSMIT.FPVR58.03.05.1900Z.xml';
    if(Filesystem::extension($sFileUrl) == Config::get('file.extension.xml'))
    {
        $oMeteoCodeXmlParser = new MeteoCodeXmlParser($sFileUrl);
        $aMeteoCodeList = $oMeteoCodeXmlParser->parse();

        Util::var_dump($aMeteoCodeList); die;
        
        $oMeteoCodeDao = new MeteoCodeDao(false);
        $oMeteoCodeDao->createMulti($aMeteoCodeList);
        $oMeteoCodeDao->deleteExpiredDataMulti($aMeteoCodeList);
        unset($oMeteoCodeDao);
        
        $oMeteoCodeDao2 = new MeteoCodeDao(true, array('sLocationCode' => 'r71.1'));
        $oMeteoCode = $oMeteoCodeDao2->getFirstElement();
        unset($oMeteoCodeDao2);
        
        Util::var_dump($oMeteoCode);
    }
}
catch (Exception $ex)
{
    echo ($ex->getMessage());
}

$sOutput = trim(ob_get_contents());

ob_end_clean();

if(!empty($sOutput))
{
    echo $sOutput;
//    mail('molinspa@iro.umontreal.ca', '[Meteocode] Test de sync', $sOutput);
}