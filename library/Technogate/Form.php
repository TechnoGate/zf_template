<?php

/**
 * Technogate
 *
 * @package Technogate_Form
 * @author Wael Nasreddine (TechnoGate) <wael@technogate.fr>
 */
class Technogate_Form extends Zend_Form {


  /**
   * Constructor
   *
   * Registers form view helper as decorator
   *
   * @param mixed $options
   * @return void
   */
  public function __construct( $options = null ) {

    /** Call the parent __construct */
    parent::__construct($options);

    /** Set the translator */
    $this->setTranslator(Zend_Validate_Abstract::getDefaultTranslator());
  }

  /**
   * Generate a captcha element
   *
   * @return Zend_Form_Element_Captcha
   */
  protected function _generateCaptcha($label = null) {

    if($label === null) {
      $label = __("Verification Code : *");
    }

    // Get the frontController instance
    $frontController = Zend_Controller_Front::getInstance();

    // Get the fontPath
    $fontPath = App::getProperty('fontPath');

    // Get the font
    $font = App::getProperty('captcha.font');

    // Generate the captcha element
    $captcha = new Zend_Form_Element_Captcha('captcha', array (
      'label' => $label,
      'captcha' => 'Image',
      'captchaOptions' => array (
        'captcha' => 'Image',
        'wordLen' => 6,
        'timeout' => 300,
        'font' => $fontPath . DIRECTORY_SEPARATOR . $font,
        'imgDir' => ROOT_PATH . '/public/img/captcha',
        'imgUrl' => $frontController->getBaseUrl() . '/img/captcha/' )
      ));

    return $captcha;
  }
}

?>