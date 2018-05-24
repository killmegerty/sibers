<?php

namespace App\Service;
use App\Model\User;

class ACL {
  function __construct() {}

  public static function login($email, $password) {
    $hashedPassword = self::hashUserPassword($password);
    $userModel = new User();
    $user = $userModel->getByEmail($email);
    if ($user && $user['password'] === $hashedPassword) {
      $_SESSION['user'] = $user;
      return true;
    }

    return false;
  }

  public static function hashUserPassword($password) {
    return hash('sha256', $password);
  }

  public static function isAuthorized() {
    if (!empty($_SESSION['user'])) {
      $userModel = new User();
      $user = $userModel->getByEmail($_SESSION['user']['email']);
      if (!empty($user)) {
        return true;
      } else {
        self::logout();
      }
    }
    return false;
  }

  public static function logout() {
    unset($_SESSION['user']);
  }
}
