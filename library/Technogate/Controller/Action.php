<?php

/**
 * Technogate
 *
 * @package Technogate_Controller_Action
 * @author Wael Nasreddine (TechnoGate) <wael@technogate.fr>
 */
abstract class Technogate_Controller_Action extends Zend_Controller_Action {

  /**
   *
   * @var Zend_Db_Adapter_Abstract
   */
  protected $_dbAdapter;

  /**
   *
   * @var Zend_Controller_Action_Helper_FlashMessenger
   */
  protected $_flashMessenger;

  /**
   *
   * @var Zend_Config
   */
  protected $_config;

  /** Redefine the init function */
  public function init() {

    /** Call the parent init */
    parent::init();

    /** Get the request */
    $request = $this->getRequest();

    $this->view->controllerName = $request->getControllerName();
    $this->view->actionName = $request->getActionName();

    /** Make the flash messenger available */
    $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');

    /** Get the messages from flashmessenger */
    $this->view->flash_messages = $this->_flashMessenger->getMessages();

    /** Build the HTML for the flash_messages */
    if(!empty($this->view->flash_messages)) {
      $this->view->flash_messages_html = '<div id="flash_messages"><ul class="list_style_none">';
      foreach($this->view->flash_messages as $fm) {
        if(is_array($fm)) {
          list($key, $message) = each($fm);
          $this->view->flash_messages_html .= sprintf('<li class="%s">%s</li>', $key, $message);
        } else {
          $this->view->flash_messages_html .= sprintf('<li>%s</li>', $fm);
        }
      }
      $this->view->flash_messages_html .= '</ul></div>';
    }
  }

  /**
   * Get Param safe
   *
   * @param string $paramName
   * @param string $default
   * @param array $filterOptions
   * @return mixed
   */
  protected function _getParamSafe($paramName, $default = null, $filterOptions = null) {

    $origParamValue = $this->_getParam ( $paramName, $default );

    if (is_array ( $origParamValue )) {
      $result = $origParamValue;
    } else {
      $result = Input::filter ( $this->_getParam ( $paramName, $default ), $filterOptions );
    }

    return $result;
  }

  /**
   * Accessor to set $_dbAdapter
   *
   * @param $db
   */
  public function setDbAdapter($db) {

    $this->_dbAdapter = $db;
  }

  /**
   * Accessor to set $_config
   *
   * @param Zend_Config $config
   */
  public function setConfig(Zend_Config $config) {

    $this->_config = $config;
  }

  /**
   * Accessor to get $_config
   *
   * @return Zend_Config
   */
  public function getConfig() {

    return $this->_config;
  }

  public function preDispatch() {

  }
}
?>