<?php

namespace App\Controller;
use App\Controller\AppController;
use App\Model\User;
use App\Service\ACL;

class IndexController extends AppController {
  public function index() {
    $this->view->set('test', json_encode(['test' => 'asdasd']));
  }

  public function test() {
    echo 'authorized';
  }

  public function login() {
    if (isset($_POST['email']) && isset($_POST['password'])) {
      ACL::login($_POST['email'], $_POST['password']);
    }
    if (ACL::isAuthorized()) {
      $this->_redirect('/game');
    }
  }
}
