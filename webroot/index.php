<?php

session_start();

// composer autoloader
require dirname(__DIR__) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

// debugger
use App\Service\Debugger;
$debugger = new Debugger();
$GLOBALS['debugger'] = $debugger;
$debugger->startPageLoad();

// config\constants
require dirname(__DIR__) . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'config.php';

// router
require dirname(__DIR__) . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'router.php';

$debugger->endPageLoad();

if (DEBUGGER) {
  include dirname(__DIR__) . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'View' . DIRECTORY_SEPARATOR . 'Element' . DIRECTORY_SEPARATOR . 'debugger.php';
}
