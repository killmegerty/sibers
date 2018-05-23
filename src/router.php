<?php

use App\Service\Router;

$router = new Router();


// custom routes
$router->addCustomRoute('/asd', 'IndexController', 'test');


$router->initRoutes();
