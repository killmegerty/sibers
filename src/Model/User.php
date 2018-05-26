<?php

namespace App\Model;

use App\Model\Player;

class User extends Model {
  public function __construct() {
    parent::__construct('users');
  }

  public function getByEmail($email) {
    $result = $this->_db->query('SELECT * FROM ' . $this->_table . ' WHERE email = \'' . $email . '\'');
    return $result->fetch_assoc();
  }

  public function createWithPlayer($data) {
    $user = $this->create($data);
    $playerModel = new Player();
    $playerModel->create([
      'user_id' => $user['id'],
      'health' => Player::HEALTH,
      'damage' => Player::DAMAGE,
      'status' => Player::STATUS_READY
    ]);
    return $user;
  }
}
