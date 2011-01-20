<?php

/**
 * Technogate
 *
 * @package Database
 * @author Wael Nasreddine (TechnoGate) <wael@technogate.fr>
 */
class Helper_Database extends Zend_Controller_Action_Helper_Abstract {

  public function __construct() {}

    public function preDispatch() {

      //$bootstrap = $this->getActionController()->getInvokeArg('bootstrap');
      //$dbAdapter = $bootstrap->getResource('db');
      //$this->getActionController()->setDbAdapter($dbAdapter);
    }
}

?>