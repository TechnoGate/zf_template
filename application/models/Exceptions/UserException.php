<?php

class UserException extends TechnogateException {

  /**
   * CODES, must be constants
   */

  /** No user currently logged in */
  const NO_USER_CURRENTLY_LOGGED_IN = 1;

  /** No user has been given (userId or email) */
  const NO_USER_GIVEN = 2;

  /** Unknown email code and string */
  const UNKNOWN_EMAIL = 3;

  /** The error for a not found user */
  const USER_NOT_FOUND_IN_DATABASE = 4;

  /** The user name already exists */
  const USER_NAME_ALREADY_EXISTS = 5;

  /** The email already exists */
  const EMAIL_ALREADY_EXISTS = 6;

  /** Column can't be determined */
  const USER_OR_EMAIL_MUST_BE_GIVEN = 7;

  /** Constants related to login errors */
  const ACCOUNT_NOT_ACTIVATED = 8;
  const UNKNOWN_STATUS = 9;
  const LOGIN_FAILED = 10;
  const ACCOUNT_BLOCKED = 11;

  /** error Messages */
  protected function _initErrorMessages() {

    self::$_errorMessages = array (
      1 => __("No user is currently logged in"),
      2 => __("No user has been given"),
      3 => __("This email is not associated with a user"),
      4 => __("The user can't be found in the database"),
      5 => __("The user name already exists, please choose another."),
      6 => __("The email already exists."),
      7 => __("You must provide a username or a password"),
      8 => __("You need to activate your account, please check your email."),
      9 => __("Your account status cannot be determined, please report this."),
      10 => __("Login failed"),
      11 => __("Your account has been blocked."),
      12 => __("No facebook account associated with this account"),
    );
  }
}
?>