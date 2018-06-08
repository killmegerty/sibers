<?php

// composer autoloader
require dirname(__DIR__) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

// config\constants
require dirname(__DIR__) . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'config.php';

use App\Service\ErrorHandler;

try {
  // router
  require dirname(__DIR__) . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'routes.php';
} catch (\Exception $e) {
  $errorHandler = new ErrorHandler($e);
  $errorHandler->render();
}
