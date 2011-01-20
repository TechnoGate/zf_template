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

      $registry = Zend_Registry::getInstance();
      $doctrine = $registry->get('doctrine');
      $dbAdapter = $doctrine['manager'];
      $this->getActionController()->setDbAdapter($dbAdapter);
    }
}

?>