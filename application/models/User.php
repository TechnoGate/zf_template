<?php

class User extends ActiveRecord\Model {

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
  public function validate() {

    if(!empty($this->_password) && $this->_password != $this->_password_confirmation)
      $this->errors->add('password_confirmation', 'Password confirmation does not match password');
    if($this->is_new_record() && empty($this->_password))
      $this->errors->add('password', 'Password is required');
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

  /**
   * This function manages account validation (by email)
   *
   * @param integer	$userId
   */
  public function sendActivationMail() {

    /** Generate the url */
    $url = Url::getBaseUrl($includeServer = true) . '/t/' . $this->tokens[0]->hash;

    /** Generate the body */
    $body = Mail::getTemplatedMail('send-account-activation.phtml', array('url' => $url));

    /** Send the email */
    Mail::send(__('activate your account'), $this->to_array(), $body);
  }

  /**
   * This function logs in a user if login / password matched
   *
   * @param string $login                         : The username/email of the user.
   * @param string $password                      : The plain text password
   * @param string $authenticateWithoutPassword   : Allow authentification without password. Default: false
   * @return integer
   * @throws Technogate_Exceptions_UserServiceException
   */
  public static final function login($login, $password) {

    /** Which column to use ? */
    if (!is_numeric($login) && strpos($login, '@') === false) {
      $column = 'login';
    } else if (!is_numeric($login) && strpos($login, '@') !== false) {
      $column = 'email';
    } else if (is_numeric($login)) {
      $column = 'id';
    } else {
      /** Raise an exception if not */
      throw new Technogate_Exceptions_TechnogateException(
        Technogate_Exceptions_UserServiceException::USER_OR_EMAIL_MUST_BE_GIVEN
      );
    }

    $authAdapter = new ZendX_Auth_Adapter_Activerecord();
    $authAdapter
      ->setModel('User')
      ->setIdentityColumn($column)
      ->setCredentialColumn('hashed_password')
      ->setIdentity($login)
      ->setCredential($password)
      ->setCredentialTreatment(Security::getZendAuthAdapterPasswordTreatment());

    /** do the authentication */
    $auth = Zend_Auth::getInstance();
    $result = $auth->authenticate($authAdapter);

    /** Check if the login is valid */
    if ($result->isValid()) {

      /** Get the data from the auth Adapter */
      $data = $authAdapter->getResultRowObject(null, 'hashed_password');

      /** find the user object */
      $user = User::find($data->id);

      /** Update the last_login field */
      // XXX: Update the last_login time

      /** Check if the user is allowed to login */
      if ($data->status == self::COMPLETED && $data->blocked == 0) {
        /**
         * ZF-7546 - prevent multiple succesive calls from storing inconsistent results
         * Ensure storage has clean state
         */
        if ($auth->hasIdentity())
          $auth->clearIdentity();

        /** Record the identity's information, in the auth storage */
        $auth->getStorage()->write($data);
      } else { // the status is not COMPLETED
        /** Clear the identitu */
        $auth->clearIdentity();

        /** Check if the account is blocked */
        if($data->blocked == 1)
          throw new Technogate_Exceptions_UserServiceException(
            Technogate_Exceptions_UserServiceException::ACCOUNT_BLOCKED
          );

        /** Check the account status */
        switch ($data->status) {
        case self::WAITACTIVATION :
          throw new Technogate_Exceptions_UserServiceException(
            Technogate_Exceptions_UserServiceException::ACCOUNT_NOT_ACTIVATED
          );
          break;
        default :
          throw new Technogate_Exceptions_UserServiceException(
            Technogate_Exceptions_UserServiceException::UNKNOWN_STATUS
          );
          break;
        } // switch
      }
    } else {
      /** Clear the identitu */
      $auth->clearIdentity();

      /** login failed */
      throw new Technogate_Exceptions_UserServiceException(
        Technogate_Exceptions_UserServiceException::LOGIN_FAILED
      );
    }

    return $result;
  }
}

?>