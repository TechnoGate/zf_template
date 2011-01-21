<?php

class Base_User extends ActiveRecord\Model {

  const WAITACTIVATION = "WAITACTIVATION";

  const COMPLETED = "COMPLETED";

  const USER_MIN_LENGTH = 4;

  const USER_MAX_LENGTH = 20;

  const PASS_MIN_LENGTH = 6;

  const PASS_MAX_LENGTH = 20;


  // Accesstible Attributes
  static $attr_accessible = array(
    'login',
    'name',
    'email',
    'phone',
    'profile_picture',
    'status',
    'blocked',
  );

  // Virtual Attributes
  protected $_password;
  protected $_password_confirmation;

  // Validations
  static $validates_presence_of = array(
    array('login'),
    array('hashed_password'),
    array('name'),
    array('email'),
    array('blocked'),
  );
  static $validates_uniqueness_of = array(
    array('login'),
    array('email'),
  );
  static $validates_format_of = array(
    array('email', 'with' => '/^[^0-9][A-z0-9_]+([.][A-z0-9_]+)*[@][A-z0-9_]+([.][A-z0-9_]+)*[.][A-z]{2,4}$/')
  );
  public function validate() {

    if(!empty($this->_password) && $this->_password != $this->_password_confirmation)
      $this->errors->add('password_confirmation', __('Password confirmation does not match password'));
    if($this->is_new_record() && empty($this->_password))
      $this->errors->add('password', __('Password is required'));
  }

  // Hooks
  static $before_validation_on_create = array('before_validation_on_create');
  static $after_create = array('after_create');

  // Associations
  static $has_many = array(
    array('tokens',
      'conditions'  => array('thing_type = ?', 'users'),
      'foreign_key' => 'thing_id'
    ),
    array('logs'),
    array('events',
      'conditions'  => array('thing_type = ?', 'users'),
      'foreign_key' => 'thing_id'
    ),
  );

  // Functions
  public function before_validation_on_create() {

    $this->init_status_attr();
    $this->init_blocked_attr();
  }

  public function after_create() {

    $this->generate_token();

    $this->sendActivationMail();
  }

  public function generate_token() {

    $token = new Token(array(
      'thing_id'    => $this->id,
      'thing_type'  => 'users',
      'action'      => 'default/users/activate',
      'auto_delete' => true,
    ));

    if(!$token->save())
      $this->errors->add('tokens', $token->errors);
  }

  public function init_blocked_attr() {

    $this->assign_attribute('blocked', false);
  }

  public function init_status_attr() {

    $this->assign_attribute('status', self::WAITACTIVATION);
  }

  public function set_password($password) {

    $this->_password = $password;
    $this->hashed_password = Security::encodePassword($this->_password);
  }

  public function set_password_confirmation($password_confirmation) {

    $this->_password_confirmation = $password_confirmation;
  }
}

?>