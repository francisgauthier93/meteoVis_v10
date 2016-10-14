<?php

ob_start();

header('Content-Type: text/html; charset=utf-8');

/*** error reporting on ***/
error_reporting(-1);
ini_set('display_errors', 'On');

define('REAL_PATH_ROOT', realpath('./') . '/');
require_once REAL_PATH_ROOT . 'autoloader.php';

ob_end_clean();

require 'app/index.php';