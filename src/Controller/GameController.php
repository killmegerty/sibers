<?php

namespace App\Controller;
use App\Controller\AppController;
use App\Model\User;
use App\Model\Player;
use App\Model\Rating;
use App\Service\ACL;

class GameController extends AppController {
  public $restrictedAccessMethods = ['index', 'duels'];

  public function index() {
  }

  public function duels() {
    $authorizedUser = ACL::getAuthorizedUser();

    $playerModel = new Player();
    $player = $playerModel->getByUserId($authorizedUser['id']);

    $this->view->set('inQueue', false);
    if (isset($_POST['startDuel'])) {
      $this->view->set('inQueue', true);
      $this->view->set('autoReloadPage', true);
      // add user in queue; check opponent
      $player['status'] = Player::STATUS_IN_QUEUE;
      $playerModel->update($player['id'], $player);
    }
    if (isset($_POST['quitQueue'])) {
      $this->view->set('inQueue', false);
      $this->view->set('autoReloadPage', false);
      // add user in queue; check opponent
      $player['status'] = Player::STATUS_READY;
      $playerModel->update($player['id'], $player);
    }


    // get from DB status - in queue or not
    if ($player['status'] == Player::STATUS_IN_QUEUE) {
      // search opponent
      $this->view->set('inQueue', true);
      $this->view->set('autoReloadPage', true);
    }

    // rating
    $ratingModel = new Rating();
    $result = $ratingModel->getByUserId($authorizedUser['id']);
    if ($result) {
      $this->view->set('userRating', $result);
    } else {
      $this->view->set('userRating', false);
    }
  }
}
