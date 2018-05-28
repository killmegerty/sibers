<?php

namespace App\Service;

class DB {
  public $debugger;

  public function __construct() {
    $this->_dbConnect(DATABASE_HOST, DATABASE_USER, DATABASE_PASSWORD, DATABASE_NAME);
    if ($GLOBALS['debugger']) {
      $this->debugger = $GLOBALS['debugger'];
    } else {
      echo 'debugger set error';
      exit();
    }
  }

  protected function _dbConnect($host, $user, $pass, $dbName) {
    $this->_db = new \mysqli($host, $user, $pass, $dbName);
    if ($this->_db->connect_errno) {
      // connection error
      echo 'db connect error';
      exit();
    }
  }

  public function fetch($queryMask, $paramsTypesMask = '', $params = []) {
    $tempParams[] = &$paramsTypesMask;
    for ($i = 0; $i < count($params); $i++) {
      $tempParams[] = &$params[$i];
    }

    $stmt = $this->_db->prepare($queryMask);
    if ($stmt) {
      $this->debugger->startDBRequest();
      if ($paramsTypesMask) {
        call_user_func_array([$stmt, 'bind_param'], $tempParams);
      }
      $stmt->execute();
      $res = $stmt->get_result();
      $row = $res->fetch_all(MYSQLI_ASSOC);
      $stmt->close();
      $this->debugger->endDBRequest();
      if ($row) {
        return $row[0];
      }
    }
    return NULL;
  }

  public function fetchAll($queryMask, $paramsTypesMask = '', $params = []) {
    $tempParams[] = &$paramsTypesMask;
    for ($i = 0; $i < count($params); $i++) {
      $tempParams[] = &$params[$i];
    }

    $stmt = $this->_db->prepare($queryMask);
    if ($stmt) {
      $this->debugger->startDBRequest();
      if ($paramsTypesMask) {
        call_user_func_array([$stmt, 'bind_param'], $tempParams);
      }
      $stmt->execute();
      $res = $stmt->get_result();
      $rows = $res->fetch_all(MYSQLI_ASSOC);
      $stmt->close();
      $this->debugger->endDBRequest();
      if ($rows) {
        return $rows;
      }
    }
    return NULL;
  }

  public function delete($queryMask, $paramsTypesMask = '', $params = []) {
    $tempParams[] = &$paramsTypesMask;
    foreach ($params as $param) {
      $tempParams[] = &$param;
    }

    $stmt = $this->_db->prepare($queryMask);
    if ($stmt) {
      $this->debugger->startDBRequest();
      if ($paramsTypesMask) {
        call_user_func_array([$stmt, 'bind_param'], $tempParams);
      }
      $stmt->execute();
      $res = $stmt->get_result();
      $stmt->close();
      $this->debugger->endDBRequest();
      if ($res) {
        return $res;
      }
    }
    return false;
  }

  public function query($query) {
    $this->debugger->startDBRequest();
    $res = $this->_db->query($query);
    $this->debugger->endDBRequest();
    return $res;
  }

  public function getLastInsertedId() {
    return $this->_db->insert_id;
  }

}
