<?php

namespace App\Controller;
use App\Controller\AppController;
use App\Model\User;
use App\Model\Player;
use App\Model\Rating;
use App\Model\Game;
use App\Model\GameLog;
use App\Service\ACL;

class GameController extends AppController {
  public $restrictedAccessMethods = ['index', 'duels', 'winner', 'loser'];

  public function index() {
  }

  public function winner() {
    $authorizedUser = ACL::getAuthorizedUser();
    $playerModel = new Player();
    $playerModel->setReadyStatusByUserId($authorizedUser['id']);
  }

  public function loser() {
    $authorizedUser = ACL::getAuthorizedUser();
    $playerModel = new Player();
    $playerModel->setReadyStatusByUserId($authorizedUser['id']);
  }

  public function duels() {
    $authorizedUser = ACL::getAuthorizedUser();

    $ratingModel = new Rating();
    $playerModel = new Player();
    $player = $playerModel->getByUserId($authorizedUser['id']);
    $gameModel = new Game();
    $gameLogModel = new GameLog();
    $game = null;
    $opponentPlayer = null;
    if ($player['game_id'] && $player['status'] == Player::STATUS_IN_GAME) {
      $game = $gameModel->get($player['game_id']);
      if ($game['player_1_id'] == $player['id']) {
        $opponentPlayer = $playerModel->getByUserId($game['player_2_user_id']);
      } else {
        $opponentPlayer = $playerModel->getByUserId($game['player_1_user_id']);
      }
    }

    // form handlers
    if (isset($_POST['startDuel'])) {
      $this->view->set('playerStatus', Player::STATUS_IN_QUEUE);
      $this->view->set('autoReloadPage', true);
      $player['status'] = Player::STATUS_IN_QUEUE;
      $playerModel->updateByUserId($player['user_id'], $player);
    }
    if (isset($_POST['quitQueue'])) {
      $this->view->set('playerStatus', Player::STATUS_READY);
      $this->view->set('autoReloadPage', false);
      $player['status'] = Player::STATUS_READY;
      $playerModel->updateByUserId($player['user_id'], $player);
    }
    if (isset($_POST['hitOpponent']) && isset($game)) {
      $gameLogModel->addLogMsg($game['id'], 'Player ID:' . $player['id'] . ' hit Player ID:' . $opponentPlayer['id'] . ' on ' . $player['damage'] . ' damage');
      if ($game['player_1_id'] == $player['id']) {
        $game['player_2_health'] = $game['player_2_health'] - $game['player_1_damage'];
      } else {
        $game['player_1_health'] = $game['player_1_health'] - $game['player_2_damage'];
      }
      if ($game['player_1_health'] <= 0 || $game['player_2_health'] <= 0) {
        $playerOne = $playerModel->getByUserId($game['player_1_user_id']);
        $playerTwo = $playerModel->getByUserId($game['player_2_user_id']);

        if ($game['player_1_health'] <= 0) {
          $game['winner_player_id'] = $game['player_2_id'];

          $ratingOne = $ratingModel->getByPlayerId($playerOne['id']);
          $ratingOne['rating'] -= 1;
          $ratingModel->update($ratingOne['id'], $ratingOne);

          $ratingTwo = $ratingModel->getByPlayerId($playerTwo['id']);
          $ratingTwo['rating'] += 1;
          $ratingModel->update($ratingTwo['id'], $ratingTwo);
        }
        if ($game['player_2_health'] <= 0) {
          $game['winner_player_id'] = $game['player_1_id'];

          $ratingOne = $ratingModel->getByPlayerId($playerOne['id']);
          $ratingOne['rating'] += 1;
          $ratingModel->update($ratingOne['id'], $ratingOne);

          $ratingTwo = $ratingModel->getByPlayerId($playerTwo['id']);
          $ratingTwo['rating'] -= 1;
          $ratingModel->update($ratingTwo['id'], $ratingTwo);
        }
        $playerOne['damage'] += 1;
        $playerOne['health'] += 1;
        $playerTwo['damage'] += 1;
        $playerTwo['health'] += 1;
        $playerModel->updateByUserId($playerOne['user_id'], $playerOne);
        $playerModel->updateByUserId($playerTwo['user_id'], $playerTwo);
      }
      $gameModel->update($game['id'], $game);

      $this->_redirectGameOver($game, $player);
    }

    if ($player['status'] == Player::STATUS_READY) {
      $rating = $ratingModel->getByUserId($authorizedUser['id']);
      if ($rating) {
        $this->view->set('rating', $rating);
      }
      $this->view->set('playerStatus', Player::STATUS_READY);
      $this->view->set('autoReloadPage', false);
      $this->view->set('player', $player);
    } else if ($player['status'] == Player::STATUS_IN_QUEUE) {
      // search opponent
      $opponentPlayer = $playerModel->findOpponentPlayer($player['id']);

      if ($opponentPlayer) {
        // create game
        $game = $gameModel->create([
          'player_1_id' => $player['id'],
          'player_1_user_id' => $player['user_id'],
          'player_1_health' => $player['health'],
          'player_1_damage' => $player['damage'],
          'player_2_id' => $opponentPlayer['id'],
          'player_2_user_id' => $opponentPlayer['user_id'],
          'player_2_health' => $opponentPlayer['health'],
          'player_2_damage' => $opponentPlayer['damage'],
          'created_at' => date('Y-m-d H:i:s')
        ]);
        // update players data
        $player['status'] = Player::STATUS_IN_GAME;
        $player['game_id'] = $game['id'];
        $playerModel->updateByUserId($player['user_id'], $player);
        $opponentPlayer['status'] = Player::STATUS_IN_GAME;
        $opponentPlayer['game_id'] = $game['id'];
        $playerModel->updateByUserId($opponentPlayer['user_id'], $opponentPlayer);
      }
      $this->view->set('playerStatus', Player::STATUS_IN_QUEUE);
      $this->view->set('autoReloadPage', true);
    } else if ($player['status'] == Player::STATUS_IN_GAME) {
      $this->view->set('playerStatus', Player::STATUS_IN_GAME);
      if ($game) {
        $this->_redirectGameOver($game, $player);
        $gameLog = $gameLogModel->getLog($game['id']);
        $this->view->set('gameLog', $gameLog);
        if ($game['player_1_id'] == $player['id']) {
          $this->view->set('player', [
            'player_id' => $game['player_1_id'],
            'name' => $player['name'],
            'health' => $player['health'],
            'damage' => $player['damage'],
            'currentHealth' => $game['player_1_health'],
            'currentDamage' => $game['player_1_damage'],
            'healthPerc' => round($game['player_1_health'] / $player['health'] * 100)
          ]);
          $this->view->set('opponent', [
            'player_id' => $game['player_2_id'],
            'name' => $opponentPlayer['name'],
            'health' => $opponentPlayer['health'],
            'damage' => $opponentPlayer['damage'],
            'currentHealth' => $game['player_2_health'],
            'currentDamage' => $game['player_2_damage'],
            'healthPerc' => round($game['player_2_health'] / $opponentPlayer['health'] * 100)
          ]);
        } else {
          $this->view->set('player', [
            'player_id' => $game['player_2_id'],
            'name' => $player['name'],
            'health' => $player['health'],
            'damage' => $player['damage'],
            'currentHealth' => $game['player_2_health'],
            'currentDamage' => $game['player_2_damage'],
            'healthPerc' => round($game['player_2_health'] / $player['health'] * 100)
          ]);
          $this->view->set('opponent', [
            'player_id' => $game['player_1_id'],
            'name' => $opponentPlayer['name'],
            'health' => $opponentPlayer['health'],
            'damage' => $opponentPlayer['damage'],
            'currentHealth' => $game['player_1_health'],
            'currentDamage' => $game['player_1_damage'],
            'healthPerc' => round($game['player_1_health'] / $opponentPlayer['health'] * 100)
          ]);
        }
        $gameCreated = new \DateTime($game['created_at']);
        $now = new \DateTime();
        $diffInSeconds = $now->getTimestamp() - $gameCreated->getTimestamp();
        if ($diffInSeconds < 30) {
          $this->view->set('autoReloadPage', false);
          $this->view->set('delayedAttackBtn', true);
        } else {
          $this->view->set('autoReloadPage', true);
          $this->view->set('delayedAttackBtn', false);
        }
      }
    }

  }

  protected function _redirectGameOver($game, $currentPlayer) {
    if (isset($game['winner_player_id'])) {
      if ($game['winner_player_id'] == $currentPlayer['id']) {
        $this->_redirect('/game/winner');
      } else {
        $this->_redirect('/game/loser');
      }
    }
  }
}
