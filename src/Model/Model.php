<?php

namespace App\Model;

use App\Service\DB;
use App\Service\Memcached;

class Model {
  protected $_table;
  protected $_db;
  public $mc;
  public $debugger;

  function __construct($table) {
    $this->_table = $table;
    $this->_db = new DB();
    $this->mc = new Memcached();
    if ($GLOBALS['debugger']) {
      $this->debugger = $GLOBALS['debugger'];
    } else {
      echo 'debugger set error';
      exit();
    }
  }

  public function test() {
    $res = $this->_db->fetch("SELECT * FROM users where id = ?", 'i', [1]);
    var_dump($res);
  }

  public function get($id) {
    return $this->_db->fetch("SELECT * FROM {$this->_table} WHERE id = ?", 'i', [$id]);
  }

  public function getAll() {
    return $this->_db->fetchAll("SELECT * FROM {$this->_table} WHERE");
  }

  public function delete($id) {
    return $this->_db->delete("DELETE FROM FROM {$this->_table} WHERE id = ?", 'i', [$id]);
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
      return $this->_db->query('SELECT * FROM ' . $this->_table . ' WHERE id = ' . $this->_db->getLastInsertedId())->fetch_assoc();
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



// namespace App\Model;
//
// use App\Service\DB;
//
// class Model {
//   protected $_table;
//   protected $_db;
//   public $mc;
//
//   function __construct($table) {
//     $this->_table = $table;
//     $this->_dbConnect(DATABASE_HOST, DATABASE_USER, DATABASE_PASSWORD, DATABASE_NAME);
//     $this->_mcConnect(MEMCACHED_HOST, MEMCACHED_PORT);
//   }
//
//   public function test() {
//     $db = new DB;
//     $res = $db->fetch("SELECT * FROM users where id = ?", 'i', [1]);
//     var_dump($res);
//   }
//
//   protected function _dbConnect($host, $user, $pass, $dbName) {
//     $this->_db = new \mysqli($host, $user, $pass, $dbName);
//     if ($this->_db->connect_errno) {
//       // connection error
//       echo 'db connect error';
//       exit();
//     }
//   }
//
//   protected function _mcConnect($host, $port) {
//     $this->mc = new \Memcached();
//     $res = $this->mc->addServer($host, $port);
//     if (!$res) {
//       // connection error
//       echo 'memcached connect error';
//       exit();
//     }
//   }
//
//   public function get($id) {
//     $stmt = $this->_db->prepare("SELECT * FROM {$this->_table} WHERE id = ?");
//     if ($stmt) {
//       $stmt->bind_param("i", $id);
//       $stmt->execute();
//       $res = $stmt->get_result();
//       $row = $res->fetch_all(MYSQLI_ASSOC);
//       $stmt->close();
//       if ($row) {
//         return $row[0];
//       }
//     }
//     return NULL;
//   }
//
//   public function getAll() {
//     $stmt = $this->_db->prepare("SELECT * FROM {$this->_table}");
//     if ($stmt) {
//       $stmt->execute();
//       $res = $stmt->get_result();
//       $rows = $res->fetch_all(MYSQLI_ASSOC);
//       $stmt->close();
//       return $rows;
//     }
//     return NULL;
//   }
//
//   public function delete($id) {
//     $stmt = $this->_db->prepare("DELETE FROM FROM {$this->_table} WHERE id = ?");
//     if ($stmt) {
//       $stmt->execute();
//       $res = $stmt->get_result();
//       return $res;
//     }
//     return false;
//   }
//
//   public function update($id, $data) {
//     $id = (int)$id;
//     $dataStrings = $this->_generateUpdateQueryStrings($data);
//
//     $result = $this->_db->query('UPDATE ' . $this->_table . ' SET ' . $dataStrings . ' WHERE id = ' . $id);
//     return $result;
//   }
//
//   public function create($data) {
//     $dataStrings = $this->_generateInsertQueryStrings($data);
//
//     $result = $this->_db->query('INSERT INTO ' . $this->_table . ' (' . $dataStrings[0] . ') VALUES (' . $dataStrings[1] . ')');
//     if ($result) {
//       return $this->_db->query('SELECT * FROM ' . $this->_table . ' WHERE id = ' . $this->_db->insert_id)->fetch_assoc();
//     }
//     return false;
//   }
//
//   protected function _generateUpdateQueryStrings($data) {
//     $output = '';
//
//     foreach ($data as $key => $value) {
//       if (strlen($value) == 0) {
//         $output .= '`' . $key . '`=NULL,';
//       } else {
//         $output .= '`' . $key . '`=\'' . $value . '\',';
//       }
//     }
//     // remove last ','
//     $output = substr($output, 0, -1);
//
//     return $output;
//   }
//
//   protected function _generateInsertQueryStrings($data) {
//     $outputKeys = '';
//     $outputValues = '';
//
//     foreach ($data as $key => $value) {
//       $outputKeys .= '`' . $key . '`,';
//       $outputValues .= '\'' . $value . '\',';
//     }
//     // remove last ','
//     $outputKeys = substr($outputKeys, 0, -1);
//     $outputValues = substr($outputValues, 0, -1);
//     return [$outputKeys, $outputValues];
//   }
// }
