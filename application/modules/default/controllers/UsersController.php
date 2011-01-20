<?php

class UsersController extends Technogate_Controller_Action {

  /** Nothing to see here, move along */
  public function indexAction() {

    $this->redirect('/');
  }

  /**
   * Logout action.
   *
   */
  public function logoutAction() {

    Zend_Auth::getInstance()->clearIdentity();
    $this->_redirect('/');
  }

  /**
   * register Action
   *
   */
  public function registerAction() {

    /** Create the form */
    $form = new Technogate_CreateUserForm();
    $form->submit->setLabel(__("Create your account"));
    $this->view->message = null;
    $this->view->form = $form;
    $this->view->form_name = $form->getName();

    /** Check if we already posted the form */
    if ($this->getRequest()->isPost()) {
      /** The form was posted, get the data */
      $formData = $this->getRequest()->getPost();
      /** Check the validity of the form data */
      if ($form->isValid($formData)) {
        /** Get the data sent by the form */
        $name = $form->getValue('name');
        $login = $form->getValue('login');
        $password = $form->getValue('password');
        $password_confirmation = $form->getValue('password_confirmation');
        $email = strtolower($form->getValue('email'));

        /** Create the account */
        $user = new User();
        $user->name = $name;
        $user->login = $login;
        $user->email = $email;
        $user->password = $password;
        $user->password_confirmation = $password_confirmation;
        if($user->save()) {
          /** Forward to the activation-link-sent action and make sure the execution endes here */
          return $this->_forward('activation-link-sent', 'users', null, array("email" => $email));
        } else {
          $this->view->errors = $user->errors->to_array();
        }
      }

      /** Valid or not, populate the data */
      $form->populate($formData);
    }

    /** Render the simple form */
    $this->render('forms/simple-form', null, true);
  }

  /**
   * login Action
   *
   */
  public function loginAction() {

    /** Create the form */
    /**
     * The target => _top is in the options to make sure the login get submmitted to the
     * main window, if for example thickbox were used, forwarding to another page after logging-in
     * will not work if target has not been set to _top.
     */
    $form = new Technogate_LoginForm(array('target' => '_top'));
    $form->submit->setLabel(__("Login"));
    $this->view->message = null;
    $this->view->form = $form;

    /** Check if we already posted the form */
    if ($this->getRequest()->isPost()) {
      /** The form was posted, get the data */
      $formData = $this->getRequest()->getPost();
      /** Check the validity of the form data */
      if ($form->isValid($formData)) {
        /** Get the data sent by the form */
        $login = $form->getValue('login');
        $password = $form->getValue('password');

        /** login */
        try {
          $loginResult = User::login($login, $password);
          /** if login was successful, redirect to the page set in the session */
          if($loginResult)
            $this->_redirect(Session::getReturnUrl('login'));
        } catch(Technogate_Exceptions_UserServiceException $e) {
          $this->view->message = $e->getMessage();
        }
      }

      /** Valid or not, populate the data */
      $form->populate($formData);
    }

    /** Render the simple form */
    $this->render('forms/simple-form', null, true);
  }

  public function activationLinkSentAction() {

    $this->view->email = $this->_getParamSafe('email');
  }

  public function activateAction() {

    /** Get the token_hash */
    $token_hash = $this->_getParamSafe('token_hash');

    /** Sanity Check */
    if (empty($token_hash))
      $this->_redirect('/');

    /** get the Token */
    $token = Token::find_by_hash($token_hash);

    /** Sanity Check */
    if(empty($token)) {
      $this->_flashMessenger->addMessage(array('error' => __("Token does not exist")));
      $this->_redirect('/');
    }
    if($token->status != Token::ACTIVE) {
      $this->_flashMessenger->addMessage(array('error' => __("Token no longer available.")));
    }

    /** Try and Activate the user */
    // XXX: Login after activation
    $user = User::find($token->thing_id);
    $user->status = User::COMPLETED;
    $result = $user->save();

    /** Initialize the params array */
    $params = array();

    /** Activate the account */
    $params = array(
      'result'      => $result,
      'redirect'    => '/users/account-activated/id/' . $user->id . '/res/' . $result,
      'id'          => $user->id,
      'token_hash'  => $token_hash,
    );

    if($result === true) {
      /** Redirect to the activated page */
      $this->_forward('account-activated', 'users', 'default', $params);
    } else {
      foreach($user->errors->full_messages() as $message)
        $this->_flashMessenger->addMessage(array('error' => $message));
      $this->_redirect('/');
    }
  }

  public function accountActivatedAction() {

    /** Get the user Id */
    $id = $this->_getParamSafe('id');

    /** send everything to the view */
    $this->view->user = User::find($id);
  }

}
?>