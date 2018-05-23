<?php

namespace App\Service;

class Router {
  protected $_customRoutes = [
    '/' => [
      'controllerName' => 'IndexController',
      'methodName' => 'index'
    ]
  ];

  function __construct() {

  }

  public function initRoutes() {
    $parsedUrl = parse_url($_SERVER['REQUEST_URI']);

    // check if custom route exists and load it
    if ($this->_isCustomRouteExists($parsedUrl['path'])) {
      $this->_loadCustomRoute($parsedUrl['path']);
      return;
    }

    $this->_loadDynamicRoutes();
  }

  protected function _loadDynamicRoutes() {
    $parsedUrl = parse_url($_SERVER['REQUEST_URI']);
    $explodedUrl = explode('/', $parsedUrl['path']);

    // $explodedUrl[1] - controller name
    if (empty($explodedUrl[1])) {
      $controllerName = 'IndexController';
    } else {
      $controllerName = ucfirst(strtolower($explodedUrl[1])) . 'Controller';
    }

    // ($explodedUrl[2]) - controller's method name
    $methodName = 'index';
    if (!empty($explodedUrl[2])) {
      $methodName = strtolower($explodedUrl[2]);
    }

    $this->_loadControllerMethod($controllerName, $methodName);
  }

  protected function _isPublicMethod($className, $methodName) {
    $obj = new \ReflectionMethod($className, $methodName);
    if ($obj->isPublic()) {
      return true;
    }
    return false;
  }

  protected function _loadControllerMethod($controllerName, $methodName) {
    $controllerFullName = '\App\Controller\\' . $controllerName;
    if (class_exists($controllerFullName)) {
      $controller = new $controllerFullName;
      if (method_exists($controller, $methodName)) {
        if ($this->_isPublicMethod('\App\Controller\\' . $controllerName, $methodName)) {
          $controller->$methodName();
          // load view
        } else {
          // 404 error redirect; no controller method
          $this->redirect('/');
        }
      } else {
        // 404 error redirect; no controller method
        $this->redirect('/');
      }
    } else {
      // 404 error redirect; no controller
      $this->redirect('/');
    }
  }

  public function redirect($url, $statusCode = 303) {
    header('Location: ' . $url, true, $statusCode);
    die();
  }

  public function addCustomRoute($routeUrl, $controllerName, $methodName) {
    $this->_customRoutes[$routeUrl] = [
      'controllerName' => $controllerName,
      'methodName' => $methodName
    ];
  }

  protected function _isCustomRouteExists($url) {
    if (isset($this->_customRoutes[$url])) {
      return true;
    }
    return false;
  }

  protected function _loadCustomRoute($url) {
    $controllerName = $this->_customRoutes[$url]['controllerName'];
    $methodName = $this->_customRoutes[$url]['methodName'];
    $this->_loadControllerMethod($controllerName, $methodName);
  }
}
