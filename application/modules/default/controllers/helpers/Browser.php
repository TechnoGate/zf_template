<?php

/**
 * Technogate
 *
 * @package Browser
 * @author Wael Nasreddine (TechnoGate) <wael@technogate.fr>
 */
class Helper_Browser extends Zend_Controller_Action_Helper_Abstract {

  public function __construct() {

  }

  protected function _getUserAgent() {

    $userAgent = $_SERVER['HTTP_USER_AGENT'];

    if(!strpos($userAgent, "MSIE 6.0") === false) {
      $browser = 'IE6';
    } else {
      $browser = 'GOOD';
    }

    return $browser;
  }

  public function preDispatch() {

    /** get the userAgent */
    $userAgent = $this->_getUserAgent();

    $this->getActionController()->browser = $userAgent;
    $this->getActionController()->view->browser = $userAgent;
  }
}

?>