<?php

/**
 * Technogate
 *
 * @package Authenticate
 * @author Wael Nasreddine (TechnoGate) <wael@technogate.fr>
 */
class Helper_Authenticate extends Zend_Controller_Action_Helper_Abstract {

  /**
   * Helper constructor.
   */
  public function __construct() {

  }

  /**
   * Called on each request to do the authentication work.
   */
  public function preDispatch() {

    // Get Zend_Auth instance.
    $auth = Zend_Auth::getInstance();

    /** Get the request */
    $request = $this->getRequest();

    /** Get the controller name / action name */
    $moduleName = $request->getModuleName();
    $controllerName = $request->getControllerName();
    $actionName = $request->getActionName();

    // If authentification is OK, then inject the identity in all controllers / views.
    if ($auth->hasIdentity()) {
      // Identity exists, get it.
      $identity = $auth->getIdentity();

      /** Make sure the idenity is in our database */
      try {
        $user = User::find($identity->id);

        /** Check if the user is blocked */
        if($this->_checkIfBlocked($user) === true) {
          /** Set the identity to false */
          $identity = false;

          /** destroy the session */
          $auth->clearIdentity();

          /** Get an instance of the Redirector helper */
          $redirector = Zend_Controller_Action_HelperBroker::getStaticHelper('Redirector');

          /** Redirect to the not-activated page */
          $redirector->setGotoSimple('user-blocked', 'error', 'default');
        }
      } catch (ActiveRecord\RecordNotFound $e) {
        /** Set the identity to false */
        $identity = false;

        /** destroy the session */
        $auth->clearIdentity();
      }
    } else {
      // Identity doesn't exist.
      $identity = false;
    }

    // Populate identity.
    $this->getActionController()->identity = $identity;
    $this->getActionController()->view->identity = $identity;
  }

  /**
   * This function check if a user is blocked
   *
   * @param User
   */
  protected function _checkIfBlocked(User $user) {

    return (bool) $user->blocked;
  }
}
?>