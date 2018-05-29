<?php

namespace App\Model;

class Game extends Model {
  public function __construct() {
    parent::__construct('games');
  }

  public function get($id) {
    $mcKeyPrefix = 'game_id_';
    if ($game = $this->mc->get($mcKeyPrefix . $id)) {
      return $game;
    } else {
      $game = $this->_db->fetch("SELECT * FROM {$this->_table} WHERE id = ?", 'i', [$id]);
      if ($game) {
        $this->mc->set($mcKeyPrefix . $id, $game);
        return $game;
      } else {
        return NULL;
      }
    }

  }

  public function update($id, $newGameData) {
    $mcKeyPrefix = 'game_id_';

    $id = (int)$id;
    $dataStrings = $this->_generateUpdateQueryStrings($newGameData);
    $this->_db->query('UPDATE ' . $this->_table . ' SET ' . $dataStrings . ' WHERE id = ' . $id);

    if ($game = $this->mc->get($mcKeyPrefix . $id)) {
      $game = array_merge($game, $newGameData);
      $this->mc->set($mcKeyPrefix . $id, $game);
    } else {
      $this->mc->set($mcKeyPrefix . $id, $newGameData);
    }
  }

  public function create($data) {
    $mcKeyPrefix = 'game_id_';
    $dataStrings = $this->_generateInsertQueryStrings($data);

    $result = $this->_db->query('INSERT INTO ' . $this->_table . ' (' . $dataStrings[0] . ') VALUES (' . $dataStrings[1] . ')');
    if ($result) {
      $game = $this->_db->query('SELECT * FROM ' . $this->_table . ' WHERE id = ' . $this->_db->getLastInsertedId())->fetch_assoc();
      $this->mc->set($mcKeyPrefix . $game['id'], $game);
      return $game;
    }
    return false;
  }
}
