<?php

/**
 * Technogate
 *
 * @package Analytics
 * @author Wael Nasreddine (TechnoGate) <wael@technogate.fr>
 */
class Helper_Analytics extends Zend_Controller_Action_Helper_Abstract {

  /**
   * Helper constructor.
   */
  public function __construct() {

  }

  /**
   * Called on each request to store statistics.
   */
  public function preDispatch() {

    /** Log every request */
    Log::logEvent(
      'LOAD',
      $_SERVER['REQUEST_URI'],
      $_SERVER['SERVER_NAME'],
      $_SERVER['REMOTE_ADDR']
    );
  }
}
?>