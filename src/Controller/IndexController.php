<?php

namespace App\Controller;
use App\Controller\AppController;
use App\Model\User;
use App\Service\ACL;

class IndexController extends AppController {
  function __construct($router, $methodName) {
    parent::__construct($router, $methodName);
  }

  public function index() {
    $this->view->set('test', json_encode(['test' => 'asdasd']));
  }

  public function test() {
    echo 'authorized';
  }

  public function login() {
    if (isset($_POST['email']) && isset($_POST['password'])) {
      $userModel = new User();
      $user = $userModel->getByEmail($_POST['email']);
      if ($user) {
        $result = ACL::login($_POST['email'], $_POST['password']);
        if (!$result) {
          $this->view->set([
            'error' => 'Wrong password',
            'email' => $_POST['email']
          ]);
        }
      } else {
        $user = $userModel->createWithPlayer([
          'email' => $_POST['email'],
          'password' => ACL::hashUserPassword($_POST['password'])
        ]);
        if ($user) {
          ACL::login($_POST['email'], $_POST['password']);
        }
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
