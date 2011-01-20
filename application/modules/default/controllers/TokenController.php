<?php

class TokenController extends Technogate_Controller_Action {

  public function indexAction() {

    /** Nothing to see here, forward back to the homePage */
    $this->_redirect('/');
  }

  public function actByHashAction() {

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

    /** Put the token_hash in the params */
    $token_action = Url::getUrlProprieties($token->action);
    $params = $token_action['params'];
    $params['token_hash'] = $token_hash;

    /** Increment the used var */
    $token->used++;

    /** Set to deleted if necessary */
    if($token->auto_delete)
      $token->status = Token::DELETED;

    /** Save changes */
    $token->save();

    /** Call the requested url */
    $this->_forward(
      $token_action['action'],
      $token_action['controller'],
      $token_action['module'],
      $params
    );
  }

}
?>