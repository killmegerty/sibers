<?php

namespace App\Model;

class User extends Model {
  public function __construct() {
    parent::__construct('users');
  }

  public function getByEmail($email) {
    $result = $this->_db->query('SELECT * FROM ' . $this->_table . ' WHERE email = \'' . $email . '\'');
    return $result->fetch_assoc();
  }
}
