<?php

namespace App\Model;

class Rating extends Model {
  const RATING = 0;

  public function __construct() {
    parent::__construct('ratings');
  }

  public function getByPlayerId($id) {
    $id = (int)$id;
    $result = $this->_db->query('SELECT * FROM ' . $this->_table . ' WHERE player_id = \'' . $id . '\'');
    return $result->fetch_assoc();
  }

  public function getByUserId($id) {
    $id = (int)$id;
    $result = $this->_db->query('SELECT ' . $this->_table . '.* FROM ' . $this->_table . ' JOIN players on players.id = ' . $this->_table . '.player_id WHERE user_id = \'' . $id . '\'');
    return $result->fetch_assoc();
  }
}
