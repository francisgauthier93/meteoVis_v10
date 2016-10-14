<?php

header('Content-Type: text/html; charset=UTF-8');

// Display errors
error_reporting(-1);
ini_set('display_errors', 'On');


$db = new PDO('mysql:host=mysql.iro.umontreal.ca;dbname=molinspa_meteo_vis;charset=utf8', 'molinspa', 'PXk2JSE_zyKeeY');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, true);
$db->setAttribute(PDO::ATTR_ORACLE_NULLS, PDO::NULL_EMPTY_STRING);
$db->setAttribute(PDO::MYSQL_ATTR_INIT_COMMAND, 'SET time_zone="+00:00";');

date_default_timezone_set('America/Montreal');

//$oDateTimeZone = new DateTimeZone(date_default_timezone_get());
//$oDateTime = new DateTime(time(), $oDateTimeZone);
//$date = $oDateTime->format('Y-m-d H:i:s');

$date = date('Y-m-d H:i:s');
//var_dump($date);

$id =  21474836472457684531;
$db->query('INSERT INTO Test VALUES ("' . $date . '", "' . $date . '", "' . $date . '", ' . $id . ');');

$stmt = $db->query('SELECT * FROM Test;');

var_dump($stmt->fetchAll(PDO::FETCH_ASSOC));
echo '<br /><br />';


//date_default_timezone_set('Europe/Paris');
//
//$stmt = $db->query('SELECT * FROM Test;');
//
//var_dump($stmt->fetchAll(PDO::FETCH_ASSOC));
//echo '<br /><br />';


// CLEAR
$db->query('TRUNCATE TABLE Test;');