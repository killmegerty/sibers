<?php

namespace App\Controller;

use App\Service\Router;
use App\View\View;

class AppController {
  protected $_router;
  public $view;

  function __construct($router, $methodName) {
    $this->_router = $router;
    $this->view = new View(get_class($this), $methodName);
    $this->$methodName();
    $this->view->render();
  }

  protected function _redirect($url, $statusCode = 303) {
    $this->_router->redirect($url, $statusCode);
  }
}
