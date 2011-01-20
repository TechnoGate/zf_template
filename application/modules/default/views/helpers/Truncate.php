<?php

class Zend_View_Helper_Truncate {

  /**
   * This function provides a helper to truncate text to a maximum length
   *
   * @param: $text, String
   * @param: $maxLength, Integer
   * @param: $toLower, Boolean, if true the string will be turned into lower case. Default: false
   * @param: $ucFirst, Boolean, if true the first character of the first word will be in upper case. Default: false
   * @param: $replaceText, String, The text that will replace what extend the maxLength. Default: "..."
   * @return string
   */
  public function truncate($text, $maxLength, $toLower = false, $ucFirst = false, $replaceText = '...') {

    /** To lower if requested */
    if($toLower === true) {
      $text = mb_strtolower($text, 'UTF-8');
    }

    /** ucfirst if requested */
    if($ucFirst === true) {
      $text = ucfirst($text);
    }

    /** truncate the text to the maxLength */
    if(strlen($text) > $maxLength) {
      $result = mb_substr($text, 0, $maxLength, 'UTF-8') . ' ' . $replaceText;
    } else {
      $result = $text;
    }

    return $result;
  }
}