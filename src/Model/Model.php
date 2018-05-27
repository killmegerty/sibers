<?php

namespace App\Model;

class Model {
  protected $_table;
  protected $_db;

  function __construct($table) {
    $this->_table = $table;
    $this->_dbConnect(DATABASE_HOST, DATABASE_USER, DATABASE_PASSWORD, DATABASE_NAME);
  }

  protected function _dbConnect($host, $user, $pass, $dbName) {
    $this->_db = new \mysqli($host, $user, $pass, $dbName);
    if ($this->_db->connect_errno) {
      // connection error
      echo 'db connect error';
      exit();
    }
  }

  public function get($id) {
    $id = (int)$id;
    $result = $this->_db->query('SELECT * FROM ' . $this->_table . ' WHERE id = ' . $id);
    return $result->fetch_assoc();
  }

  public function getAll() {
    $result = $this->_db->query('SELECT * FROM ' . $this->_table);
    return $result->fetch_all(MYSQLI_ASSOC);
  }

  public function delete($id) {
    $id = (int)$id;
    $result = $this->_db->query('DELETE FROM ' . $this->_table . ' WHERE id = ' . $id);
    return $result;
  }

  public function update($id, $data) {
    $id = (int)$id;
    $dataStrings = $this->_generateUpdateQueryStrings($data);

    $result = $this->_db->query('UPDATE ' . $this->_table . ' SET ' . $dataStrings . ' WHERE id = ' . $id);
    return $result;
  }

  public function create($data) {
    $dataStrings = $this->_generateInsertQueryStrings($data);

    $result = $this->_db->query('INSERT INTO ' . $this->_table . ' (' . $dataStrings[0] . ') VALUES (' . $dataStrings[1] . ')');
    if ($result) {
      return $this->_db->query('SELECT * FROM ' . $this->_table . ' WHERE id = ' . $this->_db->insert_id)->fetch_assoc();
    }
    return false;
  }

  protected function _generateUpdateQueryStrings($data) {
    $output = '';

    foreach ($data as $key => $value) {
      if (strlen($value) == 0) {
        $output .= '`' . $key . '`=NULL,';
      } else {
        $output .= '`' . $key . '`=\'' . $value . '\',';
      }
    }
    // remove last ','
    $output = substr($output, 0, -1);

    return $output;
  }

  protected function _generateInsertQueryStrings($data) {
    $outputKeys = '';
    $outputValues = '';

    foreach ($data as $key => $value) {
      $outputKeys .= '`' . $key . '`,';
      $outputValues .= '\'' . $value . '\',';
    }
    // remove last ','
    $outputKeys = substr($outputKeys, 0, -1);
    $outputValues = substr($outputValues, 0, -1);
    return [$outputKeys, $outputValues];
  }
}
