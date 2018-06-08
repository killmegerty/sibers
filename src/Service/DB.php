<?php

namespace App\Service;

class DB
{
  public function __construct()
  {
    $this->_dbConnect(DATABASE_HOST, DATABASE_USER, DATABASE_PASSWORD, DATABASE_NAME);
  }

  protected function _dbConnect($host, $user, $pass, $dbName)
  {
    $this->_db = new \mysqli($host, $user, $pass, $dbName);
    if ($this->_db->connect_errno) {
      throw new \Exception('DB connection error');
      exit();
    }
  }

  public function fetch($queryMask, $paramsTypesMask = '', $params = [])
  {
    $tempParams[] = &$paramsTypesMask;
    for ($i = 0; $i < count($params); $i++) {
      $tempParams[] = &$params[$i];
    }

    $stmt = $this->_db->prepare($queryMask);
    if ($stmt) {
      if ($paramsTypesMask) {
        call_user_func_array([$stmt, 'bind_param'], $tempParams);
      }
      $stmt->execute();
      $res = $stmt->get_result();
      $row = $res->fetch_all(MYSQLI_ASSOC);
      $stmt->close();
      if ($row) {
        return $row[0];
      }
    }
    return NULL;
  }

  public function fetchAll($queryMask, $paramsTypesMask = '', $params = [])
  {
    $tempParams[] = &$paramsTypesMask;
    for ($i = 0; $i < count($params); $i++) {
      $tempParams[] = &$params[$i];
    }

    $stmt = $this->_db->prepare($queryMask);
    if ($stmt) {
      if ($paramsTypesMask) {
        call_user_func_array([$stmt, 'bind_param'], $tempParams);
      }
      $stmt->execute();
      $res = $stmt->get_result();
      $rows = $res->fetch_all(MYSQLI_ASSOC);
      $stmt->close();
      if ($rows) {
        return $rows;
      }
    }
    return NULL;
  }

  public function delete($queryMask, $paramsTypesMask = '', $params = [])
  {
    $tempParams[] = &$paramsTypesMask;
    foreach ($params as $param) {
      $tempParams[] = &$param;
    }

    $stmt = $this->_db->prepare($queryMask);
    if ($stmt) {
      if ($paramsTypesMask) {
        call_user_func_array([$stmt, 'bind_param'], $tempParams);
      }
      $stmt->execute();
      $res = $stmt->get_result();
      $stmt->close();
      if ($res) {
        return $res;
      }
    }
    return false;
  }

  public function query($query)
  {
    $res = $this->_db->query($query);
    return $res;
  }

  public function getLastInsertedId()
  {
    return $this->_db->insert_id;
  }

}
