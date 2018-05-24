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
      $_SESSION['user_id'] = $user['id'];
      return true;
    }

    return false;
  }

  public static function hashUserPassword($password) {
    return hash('sha256', $password);
  }

  public static function isAuthorized() {
    if (!empty($_SESSION['user_id'])) {
      return true;
    }
    return false;
  }
}
