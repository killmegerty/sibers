<?php

namespace App\Model;

use App\Model\Player;
use App\Model\Rating;

class User extends Model {
  public function __construct() {
    parent::__construct('users');
  }

  public function getByEmail($email) {
    return $this->_db->fetch("SELECT * FROM {$this->_table} WHERE email = ?", 's', [$email]);

    // $stmt = $this->_db->prepare("SELECT * FROM {$this->_table} WHERE email = ?");
    // if ($stmt) {
    //   $stmt->bind_param("s", $email);
    //   $stmt->execute();
    //   $res = $stmt->get_result();
    //   $row = $res->fetch_all(MYSQLI_ASSOC);
    //   $stmt->close();
    //   if ($row) {
    //     return $row[0];
    //   }
    // }
    // return NULL;
  }

  public function createWithRelatedData($data) {
    $user = $this->create($data);
    $playerModel = new Player();
    $ratingModel = new Rating();
    $player = $playerModel->create([
      'user_id' => $user['id'],
      'name' => $user['email'],
      'health' => Player::HEALTH,
      'damage' => Player::DAMAGE,
      'status' => Player::STATUS_READY
    ]);
    $ratingModel->create([
      'player_id' => $player['id'],
      'rating' => Rating::RATING
    ]);
    return $user;
  }
}
