<?php

namespace App\Controller;

use App\Service\Router;
use App\View\View;

class AppController
{
  protected $_router;
  public $view;

  function __construct($router, $methodName)
  {
    $this->_router = $router;
    $this->view = new View(get_class($this), $methodName);
    $this->$methodName();
    $this->view->render();
  }

  protected function _redirect($url, $statusCode = 303)
  {
    $this->_router->redirect($url, $statusCode);
  }

  protected function _getControllerName()
  {
    $path = explode('\\', get_class($this));
    return array_pop($path);
  }

  /**
   * Check request method ajax
   *
   * @return return boolean
   */
  public function isAjax()
  {
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
    strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
      return true;
    }
    return false;
  }
}
