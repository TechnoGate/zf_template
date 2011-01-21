<?php

class Base_Token extends ActiveRecord\Model {

  /** Statuses */
  const ACTIVE = 2;
  const DISABLED = 5;
  const DELETED = 9;

  // Validations
  static $validates_presence_of = array(
    array('action'),
    array('hash'),
    array('used'),
    array('status'),
  );

  // Hooks
  static $before_validation_on_create = array('before_validation_on_create');

  // Associations

  public function before_validation_on_create() {

    $this->init_hash_attr();
    $this->init_status_attr();
    $this->init_used_attr();
  }

  public function init_hash_attr() {

    $hash = Security::generateKey($this->action);

    while(Token::count(array('conditions' => array('hash = ?', $hash))) > 0)
      $hash = Security::generateKey($this->action);

    $this->assign_attribute('hash', $hash);
  }

  public function init_status_attr() {

    $this->assign_attribute('status', self::ACTIVE);
  }

  public function init_used_attr() {

    $this->assign_attribute('used', 0);
  }
}

?>