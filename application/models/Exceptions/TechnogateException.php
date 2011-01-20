<?php

/**
 * Our Exception class
 */
abstract class TechnogateException extends Exception {

  /**
   * The error messages, an array
   *
   * array(
   *	errorCode => 'errorMessage'
   * )
   *
   * @var array
   */
  protected static $_errorMessages;

  /** The error message assigned to the error code 0
   *
   * @var string
   */
  protected static $_defaultErrorMessage;

  /** The exception is raised with only the code, which is default to 0 */
  public function __construct($code = 0, $message = null) {

    /** Initialize the errors */
    $this->_initErrorMessages();

    /** Assign the default error message */
    self::$_defaultErrorMessage = __("Unknown error has occured");

    /** If the error message is not specified, get it from the error messages */
    if($message === null) {
      if($code === 0 || !(is_array(self::$_errorMessages) && array_key_exists($code, self::$_errorMessages) === true)) {
        $message = self::$_defaultErrorMessage;
      } else {
        $message = self::$_errorMessages[$code];
      }
    }

    parent::__construct($message, $code);
  }

  /** this function is called to fill our error message */
  protected abstract function _initErrorMessages();
}

?>