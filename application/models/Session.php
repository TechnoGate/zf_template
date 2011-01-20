<?php

/**
 * Technogate
 *
 * @package Session
 * @author Wael Nasreddine (TechnoGate) <wael@technogate.fr>
 */
class Session {

  /**
   * Check that the user is logged in.
   * @return true
   */
  static public function checkSessionOpened() {

    $auth = Zend_Auth::getInstance();
    return $auth->hasIdentity();
  }

  /**
   * This function returns the currently logged in userId
   *
   * @return integer | null
   */
  public static function getLoggedInUserId() {

    $auth = Zend_Auth::getInstance();
    if($auth->hasIdentity()) {
      $result = $auth->getIdentity()->id;
    } else {
      $result = null;
    }

    return $result;
  }


  // There is a couple of modules that can be used as proxy via a redirect (ex : the login page)
  // This method update the return url to use when the process is terminated.
  // Ex : $this->_storeReturnUrl ('product/see);
  //			$this->_redirect('account/login');
  // will reroute the user on the login page, and come back to product/see page once logged.
  static public function storeReturnUrl($url, $proxyModule = 'login') {

    $_SESSION['RETURNURL'][$proxyModule] = $url;
  }

  // See above.
  public function getReturnUrl($proxyPage = 'login') {

    if (isset($_SESSION['RETURNURL'][$proxyPage])) {
      return $_SESSION['RETURNURL'][$proxyPage];
    } else {
      return '/';
    }
  }

  /**
   * This function set a variable in the session, the value $val is recorded in the $key
   *
   * @param String $key
   * @param Mixed $val
   */
  public static function set($key, $val) {

    $_SESSION[$key] = $val;
  }

  /**
   * This function unset a variable in the session
   *
   * @param String $key
   * @param coid
   */
  public static function rm($key) {

    unset($_SESSION[$key]);
  }

  /**
   * The function returns the value set in the session
   *
   * @param String $key
   * @return mixed
   */
  public static function get($key) {

    if (is_array($_SESSION) && array_key_exists($key, $_SESSION)) {
      $result = $_SESSION[$key];
    } else {
      $result = null;
    }

    return $result;
  }

}
?>