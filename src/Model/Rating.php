<?php

namespace App\Model;

class Rating extends Model {
  const RATING = 0;

  public function __construct() {
    parent::__construct('ratings');
  }

  public function getByPlayerId($id) {
    return $this->_db->fetch("SELECT * FROM {$this->_table} WHERE player_id = ?", 'i', [$id]);

    // $stmt = $this->_db->prepare("SELECT * FROM {$this->_table} WHERE player_id = ?");
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

  public function getByUserId($id) {
    return $this->_db->fetch("SELECT * FROM {$this->_table} JOIN players ON players.id = {$this->_table}.player_id WHERE user_id = ?", 'i', [$id]);

    // $stmt = $this->_db->prepare("SELECT * FROM {$this->_table} JOIN players on players.id = {$this->_table}.player_id WHERE user_id = ?");
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
}
