<?php

class TokenException extends TechnogateException {

  /**
   * CODES, must be constants
   */
  const TOKEN_DOES_NOT_EXISTS = 1;
  const TOKEN_EXPIRED = 2;

  /** error Messages */
  protected function _initErrorMessages() {

    self::$_errorMessages = array(
      '1' => __("The token does not exist"),
      '2' => __("The token has expired"),
    );
  }

}
?>