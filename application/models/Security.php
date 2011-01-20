<?php


/**
 * Security Services, provide functions used for security.
 *
 * This class should be used everywhere to generate or validate hashes!
 *
 */
class Security {

  /** Default Hash Algorythm */
  const HASH_ALGORYTHM = 'sha1';

  /** Password Salt Length */
  const PASSWORD_SALT_LENGTH = 9;

  /**
   * createHash, a function to create a hash using a generated salt.
   *
   * @param string $inText
   * @param string $saltHash
   * @param string $mode
   * @return string
   */
  public static function createHash( $inText, $saltHash = null, $algorythm = self::HASH_ALGORYTHM ) {

    /** Add a salt */
    $inText = self::_getSecretToken() . $inText . strrev(self::_getSecretToken());

    // Get some random salt, or verify a salt.
    // Added by (grosbedo AT gmail DOT com)
    if ($saltHash == null) {
      $saltHash = hash($algorythm, uniqid(rand(), true));
    }

    // Determine the length of the hash.
    $hash_length = strlen($saltHash);

    // Determine the length of the inText.
    $inText_length = strlen($inText);

    // Determine the maximum length of inText. This is only needed if
    // inText is very long. In any case, the salt will
    // be a maximum of half the end result. The longer the hash, the
    // longer the inText/salt can be.
    $inText_max_length = $hash_length / 2;

    // Shorten the salt based on the length of the inText.
    if ($inText_length >= $inText_max_length) {
      $saltHash = substr($saltHash, 0, $inText_max_length);
    } else {
      $saltHash = substr($saltHash, 0, $inText_length);
    }

    // Determine the length of the salt.
    $salt_length = strlen($saltHash);

    // Determine the salted hashed inText.
    $salted_inText = hash($algorythm, $saltHash . $inText);

    // If we add the salt to the hashed inText, we would get a hash that
    // is longer than a normally hashed inText. We don't want that; it
    // would give away hints to an attacker. Because the inText and the
    // length of the inText are known, we can just throw away the first
    // couple of characters of the salted inText. That way the salt and
    // the salted inText together are the same length as a normally
    // hashed inText without salt.
    $used_chars = ($hash_length - $salt_length) * (-1);
    $final_result = $saltHash . substr($salted_inText, $used_chars);

    return $final_result;
  }

  /**
   * Generate a key
   *
   * @param string $data
   * @return string
   */
  public static function generateKey( $inText, $algorythm = self::HASH_ALGORYTHM ) {

    // Create a hash
    $key = self::createHash($inText, null, $algorythm);

    return $key;
  }

  /**
   * validate a key against inText
   *
   * @param string $inText
   * @param string $hashedKey
   * @param string $algorythm
   * @return bool
   */
  public static function validateKey( $inText, $hashedKey, $algorythm = self::HASH_ALGORYTHM ) {

    // Create a hash
    $key = self::createHash($inText, $hashedKey, $algorythm);

    return ($key === $hashedKey);

  }

  /**
   * Warn the Administrators about a possible attack
   * @param $sourceController
   * @param $sourceAction
   * @param $params Array of params to send to the admin
   * @return void
   */
  public static function warnAdminOfPossibleAttack($sourceController, $sourceAction, $params) {

    // TODO
  }

  /**
   * This function will return the password encoded
   *
   * @param string $nativePassword : the orignal password to encode
   * @param string $salt : already existing salt. Can be the password as stored in base (salt will be extracted)
   * @return unknown
   */
  public static function encodePassword( $nativePassword, $salt = null ) {

    if ($salt === null) {
      $salt = substr(md5(uniqid(rand(), true)), 0, self::PASSWORD_SALT_LENGTH);
    } else {
      $salt = substr($salt, 0, self::PASSWORD_SALT_LENGTH);
    }

    $passwordHash = $salt . sha1($salt . $nativePassword);

    return $passwordHash;
  }

  /**
   * This function returns the algorithm that Zend_Auth_Adapter should use to validate the password
   *
   * @return string
   */
  public static function getZendAuthAdapterPasswordTreatment($authenticateWithoutPassword = false) {

    /** Are we logging-in without password? */
    if($authenticateWithoutPassword === true) {
      $result = "concat(`hashed_password`, substring(?,0,0))";
    } else {
      $result = "concat(substring(`hashed_password`,1,"
        . self::PASSWORD_SALT_LENGTH
        . "), sha1(concat(substring(`hashed_password`,1,"
        . self::PASSWORD_SALT_LENGTH . "),?)))";
    }

    return $result;
  }

  protected static function _getSecretToken() {

    return App::getProperty('app.models.security.secret_token');
  }

}

?>