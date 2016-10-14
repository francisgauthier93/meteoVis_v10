<?php

//die;

header('Content-Type: text/html; charset=UTF-8');

ob_start();

error_reporting(-1);
ini_set('display_errors', 'On');
ignore_user_abort(true);

define('REAL_PATH_ROOT', realpath('../../') . '/');
require_once REAL_PATH_ROOT . 'autoloader.php';

ini_set('max_execution_time', Config::get('app.max_execution_time'));
set_time_limit(Config::get('app.max_execution_time'));

Timer::init();

require_once(REAL_PATH_ROOT . 'lib/amqp/vendor/autoload.php');
require_once(REAL_PATH_ROOT . 'core/tool/ReceiverAmqp.php');

//define('AMQP_DEBUG', true);
define('AMQP_DEBUG', false);
define('FILE_AMQP_QUEUE', REAL_PATH_ROOT . 'tmp/amqp.queue');

try
{
    $oReceiver = new ReceiverAmqp();
    $oReceiver->listen();
}
catch (Exception $ex)
{
    echo ($ex->getMessage());
}

$sOutput = trim(ob_get_contents());

ob_end_clean();

if(!empty($sOutput))
{
    echo ($sOutput) . '<br />';
    echo 'Fin de la mise &agrave; jour';
//    mail('molinspa@iro.umontreal.ca', '[Meteocode] Batch de sync', $sOutput);
}
else
{
    echo 'Aucune mise &agrave; jour';
}