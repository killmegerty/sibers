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
    return $this->_db->fetch("SELECT * FROM {$this->_table} WHERE user_id = ?", 'i', [$id]);

    // $stmt = $this->_db->prepare("SELECT * FROM {$this->_table} WHERE user_id = ?");
    // if ($stmt) {
    //   $stmt->bind_param("i", $id);
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

  public function findOpponentPlayer($currentPlayerId) {
    $statusInQueue = self::STATUS_IN_QUEUE;
    return $this->_db->fetch("SELECT * FROM {$this->_table} WHERE id != ? AND status = ? LIMIT 1", 'is', [$currentPlayerId, $statusInQueue]);

    // $statusInQueue = self::STATUS_IN_QUEUE;
    // $stmt = $this->_db->prepare("SELECT * FROM {$this->_table} WHERE id != ? AND status = ? LIMIT 1");
    // if ($stmt) {
    //   $stmt->bind_param("is", $currentPlayerId, $statusInQueue);
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

  public function setReadyStatus($playerId) {
    $this->update($playerId, [
      'game_id' => NULL,
      'status' => Player::STATUS_READY
    ]);
  }
}
