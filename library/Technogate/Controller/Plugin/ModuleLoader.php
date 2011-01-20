<?php

/**
 * Technogate
 *
 * This plugin load a different layout path for each module
 *
 * @package Technogate_Controller_Plugin_ModuleLoader
 * @author Wael Nasreddine (TechnoGate) <wael@technogate.fr>
 */
class Technogate_Controller_Plugin_ModuleLoader extends Zend_Controller_plugin_Abstract {


  /**
   * This function will just check if the HTTP_USER_AGENT has both Mobile and Safari in it
   *
   * @return bool : True if the client is an iPhone user.
   */
  protected function _isIphone() {

    $userAgent = $_SERVER['HTTP_USER_AGENT'];

    if(!strpos($userAgent, "Mobile") === false && !strpos($userAgent, "Safari") === false) {
      $iphone = true;
    } else {
      $iphone = false;
    }

    return $iphone;
  }

  /**
   * In the predispatch, if the client is an iphone, switch to the iphone module
   */
  public function preDispatch(Zend_Controller_Request_Abstract $request) {

    if($this->_isIphone() === true) {
      $request->setModuleName('iphone');
    }
  }
}
?>