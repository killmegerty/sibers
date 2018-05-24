<?php

use App\Service\Router;

$router = new Router();


// custom routes
$router->addCustomRoute('/', 'IndexController', 'login');


$router->initRoutes();
