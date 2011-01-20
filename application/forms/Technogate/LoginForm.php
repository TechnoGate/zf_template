<?php

/**
 * Technogate
 *
 * @package Technogate_LoginForm
 * @author Wael Nasreddine (TechnoGate) <wael@technogate.fr>
 */
class Technogate_LoginForm extends Technogate_Form {

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
    $this->setName('login');

    /**
     * Add Form elements.
     */
    // User Name
    $login = new Zend_Form_Element_Text('login');
    $login
      ->setLabel(__("User Name"))
      ->setRequired(true)
      ->addFilter('StripTags')
      ->addFilter('StringTrim')
      ->addValidator('NotEmpty');

    // Password
    $password = new Zend_Form_Element_Password('password');
    $password
      ->setLabel(__("Password"))
      ->setRequired(true)
      ->addFilter('StripTags')
      ->addFilter('StringTrim')
      ->addValidator('NotEmpty');

    // Submit
    $submit = new Zend_Form_Element_Submit('submit');
    $submit->setAttrib('id', 'submitbutton');

    // Finally Add the elements to the view.
    $this->addElements(array ($login, $password, $submit ));
  }
}

?>