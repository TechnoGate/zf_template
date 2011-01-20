<?php

/**
 * Technogate
 *
 * @package Technogate_RegisterAccountForm
 * @author Wael Nasreddine (TechnoGate) <wael@technogate.fr>
 */
class Technogate_CreateUserForm extends Technogate_Form {

  /**
   * Contructor
   *
   * @param mixed $options
   * @return void
   */
  public function __construct( $options = null ) {

    // Call The parent Constructor
    parent::__construct($options);

    // Set the form name
    $this->setName('users');

    /**
     * Add Form elements.
     */
    // Name
    $name = new Zend_Form_Element_Text('name');
    $name
      ->setLabel(__("Name"))
      ->setRequired(true)
      ->addFilter('StripTags')
      ->addFilter('StringTrim')
      ->addValidator('NotEmpty');

    // User Name
    $login_length = array (User::USER_MIN_LENGTH, User::USER_MAX_LENGTH);
    $login = new Zend_Form_Element_Text('login');
    $login
      ->setLabel(__("User Name"))
      ->setRequired(true)
      ->addFilter('StripTags')
      ->addFilter('StringTrim')
      ->addValidator('NotEmpty')
      ->addValidator('StringLength', false, $login_length);

    // Password
    $password_length = array (User::PASS_MIN_LENGTH, User::PASS_MAX_LENGTH );
    $password = new Zend_Form_Element_Password('password');
    $password
      ->setLabel(__("Password"))
      ->setRequired(true)
      ->addFilter('StripTags')
      ->addFilter('StringTrim')
      ->addValidator('NotEmpty')
      ->addValidator('StringLength', false, $password_length);

    // Verify Password
    $password_confirmation = new Zend_Form_Element_Password('password_confirmation');
    $password_confirmation
      ->setLabel(__("Verify Password"))
      ->setRequired(true)
      ->addFilter('StripTags')
      ->addFilter('StringTrim')
      ->addValidator('NotEmpty')
      ->addValidator('StringLength', false, $password_length);

    // Email
    $email = new Zend_Form_Element_Text('email');
    $email
      ->setLabel(__("Email"))
      ->setRequired(true)
      ->addFilter('StripTags')
      ->addFilter('StringTrim')
      ->addValidator('NotEmpty')
      ->addValidator('EmailAddress');

    // Captcha
    $captcha = $this->_generateCaptcha();

    // Submit
    $submit = new Zend_Form_Element_Submit('submit');
    $submit->setAttrib('id', 'submitbutton');

    // Finally Add the elements to the view.
    $this->addElements(array ($name, $login, $password, $password_confirmation, $email, $captcha, $submit ));
  }
}

?>