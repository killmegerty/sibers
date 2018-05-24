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
      $result = ACL::login($_POST['email'], $_POST['password']);
      if (!$result) {
        $this->view->set([
          'error' => 'User not exists',
          'email' => $_POST['email']
        ]);
      }
    }
    if (ACL::isAuthorized()) {
      $this->_redirect('/game');
    }
  }

  public function logout() {
    if (isset($_POST['logout'])) {
      ACL::logout();
      $this->_redirect('/');
    }
  }
}
