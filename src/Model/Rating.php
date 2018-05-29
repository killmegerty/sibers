<?php

namespace App\Model;

class Rating extends Model {
  const RATING = 0;

  public function __construct() {
    parent::__construct('ratings');
  }

  public function getByPlayerId($id) {
    return $this->_db->fetch("SELECT * FROM {$this->_table} WHERE player_id = ?", 'i', [$id]);
  }

  public function getByUserId($id) {
    return $this->_db->fetch("SELECT * FROM {$this->_table} JOIN players ON players.id = {$this->_table}.player_id WHERE user_id = ?", 'i', [$id]);
  }
}
