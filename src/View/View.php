<?php

namespace App\View;

class View
{
  protected $_viewFilePath;
  protected $_variables = [];
  protected $_layout = 'default';
  protected $_controllerFullName;
  protected $_methodName;
  protected $_isRenderView = true;

  public function __construct($controllerFullName, $methodName)
  {
    $this->_controllerFullName = $controllerFullName;
    $this->_methodName = $methodName;
  }

  public function render()
  {
    if ($this->_isRenderView) {
      $layoutFilePath = dirname(__DIR__) .
        DIRECTORY_SEPARATOR .
        'View' .
        DIRECTORY_SEPARATOR .
        'Layout' .
        DIRECTORY_SEPARATOR .
        $this->_layout .
        '.php';
      if (file_exists($layoutFilePath)) {
        include $layoutFilePath;
      } else {
        // 404 error; layout not found
        echo '404 error; layout not found';
      }
    }
  }

  public function element($name)
  {
    $elementFilePath = dirname(__DIR__) .
      DIRECTORY_SEPARATOR .
      'View' .
      DIRECTORY_SEPARATOR .
      'Element' .
      DIRECTORY_SEPARATOR .
      $name .
      '.php';
    if (file_exists($elementFilePath)) {
      include $elementFilePath;
    } else {
      // 404 error; element not found
      echo '404 error; element not found';
    }
  }

  public function script($path)
  {
    return "<script src='$path'></script>";
  }

  protected function _includeViewFile()
  {
    $trimmedClassName = str_replace(['App\Controller\\','Controller'], '', $this->_controllerFullName);
    $this->_viewFilePath = dirname(__DIR__) .
      DIRECTORY_SEPARATOR .
      'View' .
      DIRECTORY_SEPARATOR .
      $trimmedClassName .
      DIRECTORY_SEPARATOR .
      $this->_methodName .
      '.php';

    if (file_exists($this->_viewFilePath)) {
      include $this->_viewFilePath;
    } else {
      // 404 error; view not found
      echo '404 error; view not found';
    }
  }

  public function set($key, $value = '')
  {
    if (is_array($key)) {
      $this->_variables = array_merge($this->_variables, $key);
    } else {
      $this->_variables[$key] = $value;
    }
  }

  public function get($key)
  {
    if (isset($this->_variables[$key])) {
      return $this->_variables[$key];
    }
  }

  public function setLayout($layoutName)
  {
    $this->_layout = $layoutName;
  }

  public function content()
  {
    $this->_includeViewFile();
  }

  public function disableRender()
  {
    $this->_isRenderView = false;
  }
}
