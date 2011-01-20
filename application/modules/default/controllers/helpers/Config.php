<?php

/**
 * Technogate
 *
 * @package Config
 * @author Wael Nasreddine (TechnoGate) <wael@technogate.fr>
 */
class Helper_Config extends Zend_Controller_Action_Helper_Abstract {

  public function __construct() {}

  public function preDispatch() {

    $bootstrap = $this->getActionController()->getInvokeArg('bootstrap');
    $config = new Zend_Config($bootstrap->getOptions());
    $this->getActionController()->setConfig($config);

    /** Give the services access to the config */
    Zend_Registry::set('config', $config);
  }

  public function getConfig() {

    return $this->getActionController()->getConfig();
  }
}

?>