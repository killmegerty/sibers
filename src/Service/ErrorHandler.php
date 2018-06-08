<?php

namespace App\Service;

class ErrorHandler
{
  protected $_view = 'error';
  protected $_exception;

  public function __construct(\Exception $e)
  {
    $this->_exception = $e;
  }

  public function render()
  {
    $e = $this->_exception;
    include dirname(__DIR__) .
      DIRECTORY_SEPARATOR .
      'View' .
      DIRECTORY_SEPARATOR .
      'Element' .
      DIRECTORY_SEPARATOR .
      $this->_view .
      '.php';
  }
}
