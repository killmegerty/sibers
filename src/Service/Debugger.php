<?php

namespace App\Service;

class Debugger {
  protected $_pageStartLoadingTime;
  protected $_pageLoadTime = 0;

  protected $_startDBReqLoadingTime;
  protected $_dbLoadTime = 0;
  protected $_dbReqCount = 0;

  protected $_startMCReqLoadingTime;
  protected $_mcLoadTime = 0;
  protected $_mcReqCount = 0;

  public function __construct() {

  }

  public function startPageLoad() {
    $this->_pageStartLoadingTime = microtime(true);
  }

  public function endPageLoad() {
    $this->_pageLoadTime += (microtime(true) - $this->_pageStartLoadingTime) * 1000;
  }

  // return ms
  public function getPageLoadTime() {
    return round($this->_pageLoadTime);
  }

  public function startDBRequest() {
    $this->_startDBReqLoadingTime = microtime(true);
    $this->_dbReqCount += 1;
  }

  public function endDBRequest() {
    $this->_dbLoadTime += (microtime(true) - $this->_startDBReqLoadingTime) * 1000;
  }

  public function getDBReqCouner() {
    return $this->_dbReqCount;
  }

  public function getDBLoadTime() {
    return round($this->_dbLoadTime);
  }

  public function startMCRequest() {
    $this->_startMCReqLoadingTime = microtime(true);
    $this->_mcReqCount += 1;
  }

  public function endMCRequest() {
    $this->_mcLoadTime += (microtime(true) - $this->_startMCReqLoadingTime) * 1000;
  }

  public function getMCReqCouner() {
    return $this->_mcReqCount;
  }

  public function getMCLoadTime() {
    return round($this->_mcLoadTime);
  }
}
