<?php

namespace App\Model;

class Player extends Model {
  const HEALTH = 100;
  const DAMAGE = 10;
  const STATUS_IN_QUEUE = 'in_queue';
  const STATUS_READY = 'ready';
  const STATUS_IN_GAME = 'in_game';

  public function __construct() {
    parent::__construct('players');
  }

  public function getByUserId($id) {
    $result = $this->_db->query('SELECT * FROM ' . $this->_table . ' WHERE user_id = \'' . $id . '\'');
    return $result->fetch_assoc();
  }
}
