<?php

namespace App\Service;
use App\Model\User;

class ACL {
  protected $_restrictedAccessMethods = [];

  function __construct() {}

  public function isMethodRequireAuth($controllerName, $methodName) {
    if (isset($this->_restrictedAccessMethods[$controllerName])) {
      foreach ($this->_restrictedAccessMethods[$controllerName] as $restrictedAccessMethod) {
        if ($restrictedAccessMethod === $methodName) {
          return true;
        }
      }
      return false;
    }
    return false;
  }

  public function addRequiredAuthMethods($controllerName, array $methodsName) {
    $this->_restrictedAccessMethods[$controllerName] = $methodsName;
  }

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
      return true;
    }
    return false;
  }

  public static function logout() {
    unset($_SESSION['user']);
  }

  public static function getAuthorizedUser() {
    if (!empty($_SESSION['user'])) {
      return $_SESSION['user'];
    }
    return false;
  }
}
