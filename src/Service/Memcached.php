<?php

namespace App\Service;

class Memcached {
  public $debugger;
  public $mc;

  public function __construct() {
    $this->_mcConnect(MEMCACHED_HOST, MEMCACHED_PORT);
    if ($GLOBALS['debugger']) {
      $this->debugger = $GLOBALS['debugger'];
    } else {
      echo 'debugger set error';
      exit();
    }
  }

  protected function _mcConnect($host, $port) {
    $this->mc = new \Memcached();
    $res = $this->mc->addServer($host, $port);
    if (!$res) {
      // connection error
      echo 'memcached connect error';
      exit();
    }
  }

  public function set($key, $value) {
    $this->debugger->startMCRequest();
    $res = $this->mc->set($key, $value);
    $this->debugger->endMCRequest();
    return $res;
  }

  public function get($key) {
    $this->debugger->startMCRequest();
    $res = $this->mc->get($key);
    $this->debugger->endMCRequest();
    return $res;
  }
}
