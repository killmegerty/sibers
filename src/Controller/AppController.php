<?php

namespace App\Controller;

use App\Service\Router;
use App\Service\ACL;
use App\View\View;

class AppController {
  protected $_router;
  protected $_acl;
  public $view;
  public $restrictedAccessMethods = [];


  function __construct($router, $methodName) {
    $this->_router = $router;
    $this->view = new View(get_class($this), $methodName);
    $this->_acl = new ACL();
    $this->_acl->addRequiredAuthMethods($this->_getControllerName(), $this->restrictedAccessMethods);
    if ($this->_acl->isMethodRequireAuth($this->_getControllerName(), $methodName)) {
      if ($this->_acl->isAuthorized()) {
        $this->$methodName();
      } else {
        $this->_redirect('/');
      }
    } else {
      $this->$methodName();
    }
    $this->view->render();
  }

  protected function _redirect($url, $statusCode = 303) {
    $this->_router->redirect($url, $statusCode);
  }

  protected function _getControllerName() {
    $path = explode('\\', get_class($this));
    return array_pop($path);
  }
}
