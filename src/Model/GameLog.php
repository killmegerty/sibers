<?php

namespace App\Model;

class GameLog extends Model {
  const MC_KEY_PREFIX = 'game_log_';

  public function __construct() {
    parent::__construct('game_logs');
  }

  public function addLogMsg($gameId, $msg) {
    $gameLog = $this->mc->get(self::MC_KEY_PREFIX . $gameId);
    if (!is_array($gameLog)) {
      $gameLog = [];
    }
    array_push($gameLog, $msg);
    $this->mc->set(self::MC_KEY_PREFIX . $gameId, $gameLog);
  }

  public function getLog($gameId) {
    return $this->mc->get(self::MC_KEY_PREFIX . $gameId);
  }
}
