<?php

namespace App\Controller;
use App\Controller\AppController;
use App\Model\User;
use App\Service\ACL;

class GameController extends AppController {
  public function index() {
    if (!ACL::isAuthorized()) {
      $this->_redirect('/');
    }
    $this->view->set('hello', 'Hello world');
  }

}
