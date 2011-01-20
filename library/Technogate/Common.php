<?php

/**
 * Technogate
 *
 * @package Technogate_Common
 * @author Wael Nasreddine (TechnoGate) <wael@technogate.fr>
 */

/**
 * Generic Debug function integrating with FirePhp
 */
function dbg($logInfo, $exit = false) {
  if (runLocal()) {
    if (! empty ( $logInfo )) {
      Zend_Registry::get ( 'firePhpLogger' )->log ( $logInfo, Zend_Log::INFO );
    }
  }
  if ($exit) {
    echo $logInfo . "<br/>";
    exit ();
  }
}

function pp($message, $object) {
  echo "<strong>" . $message . "</strong><br/>";
  echo "<pre>";
  print_r ( $object );
  echo "</pre>";
}

function pe($message, $object) {
  pp ( $message, $object );
  exit ();
}

/**
 * echo a message if debug is enabled
 * @param $msg string
 * @return void
 */
function dbgEcho($msg) {
  if(defined('DEBUG') && DEBUG === true) {
    echo $msg;
  }
}

/**
 * Translate a string.
 *
 * @param string $string
 * @param string $translationObject
 * @return string
 */
function __($string, $translationObject = 'Zend_Translate') {

  $translate = Zend_Registry::get ( $translationObject );
  return $translate->translate ( $string );
}

/**
 * Retourne une date Now pour insertion dans un champs date time.
 */
function getSqlDateNow($format = "Y-m-d H:i:s") {
  return date ( $format, $_SERVER ['REQUEST_TIME'] );
}

/**
 * Return the first line of a text.
 *
 */
function getFirstLine($a_text) {

  $l_lines = explode ( "\n", $a_text );
  return $l_lines [0];
}

/*
 * Returns true if a string starts with $prefix
 *
 */
function strStartsWith($str, $prefix, $noCase = false) {

  if (! $noCase) {
    return (substr ( $str, 0, strlen ( $prefix ) ) === $prefix);
  } else {
    return (substr ( strtolower ( $str ), 0, strlen ( $prefix ) ) === strtolower ( $prefix ));
  }
}

/*
 * Returns true if a string with with another one
 *
 */
function strEndsWith($str, $prefix, $noCase = false) {

  if (! $noCase) {
    return (substr ( $str, strlen ( $str ) - strlen ( $prefix ), strlen ( $prefix ) ) === $prefix);
  } else {
    return (substr ( strtolower ( $str ), strlen ( $str ) - strlen ( $prefix ), strlen ( $prefix ) ) === strtolower ( $prefix ));
  }
}

/*
 * Returns true if we're running a development or testing environment.
 */
function runLocal() {

  return (isDevEnvironment() || isTestingEnvironment());
}

/**
 * isDevEnvironment is a function to check which environment are we on now
 *
 * @return boot True if APPLICATION_ENV is development, false if not
 */
function isDevEnvironment() {

  return (APPLICATION_ENV === 'development');
}

/**
 * isTestingEnvironment is a function to check which environment are we on now
 *
 * @return boot True if APPLICATION_ENV is testing, false if not
 */
function isTestingEnvironment() {

  return (APPLICATION_ENV === 'testing');
}

/**
 * isProductionEnvironment() is a function to check which environment are we on now
 *
 * @return boot True if APPLICATION_ENV is production, false if not
 */
function isProductionEnvironment() {

  return (APPLICATION_ENV === 'production');
}

/**
 * Check if we are running under simpletest
 * @return bool
 */
function isSimpleTest() {

  if(defined('SIMPLETEST')) {
    $result = true;
  } else {
    $result = false;
  }

  return $result;
}

// Return the current date in a format suitable for a datetime db column
function getCurrentDate() {

  $date = new Zend_Date ( );
  $dateStr = $date->toString ( "YYYY-MM-dd HH:mm:ss" );
  return $dateStr;
}

/**
 * Returns the first element of an associative array.
 *
 * @param Array $myArray : The array for which to get the first element.
 * @return bool | mixed
 */
function getFirstElement($myArray) {

  if(!empty($myArray) && is_array($myArray)) {
    $keys = array_keys($myArray);
    $result = $myArray[$keys[0]];
  } else {
    $result = false;
  }

  return $result;
}

/**
 * Returns the last element of an associative array.
 *
 * @param Array $myArray : The array for which to get the last element.
 * @return bool | mixed
 */
function getLastElement($myArray) {

  if(!empty($myArray) && is_array($myArray)) {
    $keys = array_keys ($myArray);
    $result = $myArray[$keys[count($keys) - 1]];
  } else {
    $result = false;
  }

  return $result;
}

/**
 * Returns the first key of an associative array.
 *
 * @param Array $myArray : The array for which to get the first key.
 * @return bool | string
 */
function getFirstKey($myArray) {

  if(!empty($myArray) && is_array($myArray)) {
    $keys = array_keys($myArray);
    $result = $keys[0];
  } else {
    $result = false;
  }

  return $result;
}

/**
 * Returns the last key of an associative array.
 *
 * @param Array $myArray : The array for which to get the last key.
 * @return bool | string
 */
function getLastkey($myArray) {

  if(!empty($myArray) && is_array($myArray)) {
    $keys = array_keys($myArray);
    $result = $keys[count($keys) - 1];
  } else {
    $result = false;
  }

  return $result;
}

/**
 * Function
 *
 * @return
 */
function printArray($arrayToPrint) {

  $result = "yo";
  foreach ( $arrayToPrint as $k => $v ) {
    $result .= "[" . $k . "] =>";
    if (is_array ( $v )) {
      $result .= "array (" . printArray ( $v ) . ") \r\n";
    } else {
      //			$result .= 'CJAO?' . "\r\n";
    }
  }
  return $result;
}

/**
 * Escape chars (use in js dynamic generation)
 *
 * @return string a string
 */
function jsEscape($str) {

  return str_replace ( "'", "\'", $str );
}

function ezDate($d) {

    /**
     $l_year = substr ($d, 0,4);
     $l_month = substr ($d, 6,2);
     $l_day = substr ($d, 9,2);
     $l_hour = substr ($d, 12,2);
     $l_min = substr ($d, 15,2);
     $l_sec = substr ($d, 18,2);
    **/
  $dateArray = array ();
  $l_year = substr ( $d, 0, 4 );
  $dateArray ["year"] = $l_year;
  $l_month = substr ( $d, 5, 2 );
  $dateArray ["l_month"] = $l_month;
  $l_day = substr ( $d, 8, 2 );
  $dateArray ["l_day"] = $l_day;
  $l_hour = substr ( $d, 11, 2 );
  $dateArray ["l_hour"] = $l_hour;
  $l_min = substr ( $d, 14, 2 );
  $dateArray ["l_min"] = $l_min;
  $l_sec = substr ( $d, 17, 2 );
  $dateArray ["l_sec"] = $l_sec;
  $dateArray ["original"] = $d;

  $ts = time () - mktime ( $l_hour, $l_min, $l_sec, $l_month, $l_day, $l_year );
  //$ts = time() - strtotime(str_replace("-","/",$d));


  if ($ts > 31536000)
    $val = round ( $ts / 31536000, 0 ) . " " . ngettext ( " year", "years", round ( $ts / 31536000, 0 ) );
  else if ($ts > 2419200)
    $val = round ( $ts / 2419200, 0 ) . " " . ngettext ( " month", "months", round ( $ts / 2419200, 0 ) );
  else if ($ts > 604800)
    $val = round ( $ts / 604800, 0 ) . " " . ngettext ( " week", "weeks", round ( $ts / 604800, 0 ) );
  else if ($ts > 86400)
    $val = round ( $ts / 86400, 0 ) . " " . ngettext ( " day", " days", round ( $ts / 86400, 0 ) );
  else if ($ts > 3600)
    $val = round ( $ts / 3600, 0 ) . " " . ngettext ( " hour", " hours", round ( $ts / 3600, 0 ) );
  else if ($ts > 60)
    $val = round ( $ts / 60, 0 ) . " " . ngettext ( " minute", " minutes", round ( $ts / 60, 0 ) );
  else
    $val = $ts . " " . ngettext ( " second", " seconds", $ts );
  //if($val>1) $val .= 's';
  //$value = str_replace ('moiss', 'mois', $val);
  $value = $val;
  return $value;
}

function pluralize($a_qty, $a_singular, $a_plural, $a_displayQty = true, $options = null) {

  if (empty ( $options ['emptyString'] )) {
    $options ['emptyString'] = 'Aucun';
  }
  if ($a_qty > 1) {
    if ($a_displayQty) {
      return $a_qty . " " . $a_plural;
    } else {
      return $a_plural;
    }
  } else if ($a_qty == 1) {
    if ($a_displayQty) {
      return "1 " . $a_singular;
    } else {
      return $a_singular;
    }
  } else {
    if ($a_displayQty) {
      return $options ['emptyString'] . " " . $a_singular;
    } else {
      return $a_singular;
    }
  }
}

/**
 * Cuts off long URLs at $url_length, and appends "..."
 *
 * @param string $url
 * @param int $url_length
 * @return string
 */
function reduceurl($url, $url_length) {

  $reduced_url = substr ( $url, 0, $url_length );
  if (strlen ( $url ) > $url_length)
    $reduced_url .= '...';
  return $reduced_url;
}

/**
 * Make links clickable in the given text
 *
 * @param string $text
 * @return string
 */
function clickable($text) {

  $text = str_replace ( "\\r", "\r", $text );
  $text = str_replace ( "\\n", "\n<BR>", $text );
  $text = str_replace ( "\\n\\r", "\n\r", $text );

  $text = preg_replace_callback ( '`(?<!href=["\'])(?<!src=["\'])(?<!\>)((?:https?|ftp)://\S+[[:alnum:]]/?)`si', create_function ( '$matches', 'return "<a href=\"$matches[0]\" rel=nofollow>" . reduceurl($matches[1],50) . "</a>";' ), $text );

  $text = preg_replace_callback ( '`((?<!//)(www\.\S+[[:alnum:]]/?))`si', create_function ( '$matches', 'return "<a href=\"http://$matches[0]\" rel=\'nofollow\'>" . reduceurl($matches[1],50) . "</a>";' ), $text );

  return $text;
}

/**
 * Make a number readble, as in 1ère, 2ème, 71ème etc..
 * @param $number
 * @param $sup if set to true the ème will be surrounded by <sup></sup>
 * @param $single
 * @param $plural
 * @return string
 */
function makeReadableNum($number, $sup = false, $single = 'er', $plural = "ème") {

  if($number == 1) {
    $result = $number . supHtml($single, $sup);
  } else {
    $result = $number . supHtml($plural, $sup);
  }

  return $result;
}

function supHtml($text, $sup = false) {

  if($sup === true) {
    $text = '<sup>' . $text . '</sup>';
  }

  return $text;
}

/**
 * This function return all emails as an array, emails from the log file
 *
 * @return Array
 */
function getMailsFromLog() {

  /** Define the log file to open */
  $logFile = ROOT_PATH . '/temp/logs/mail.log';

  /** Open the file */
  $fh = fopen($logFile, 'r');

  /** read all contents */
  $logContents = fread($fh, filesize($logFile));

  /** Split the contents using the seperator defined in the mail class */
  $emails = explode(Technogate_Mail::SEPARATOR, $logContents);

  /** remove empty emails */
  foreach($emails as $k => &$email) {
    $email = trim($email);
    if(empty($email)) {
      unset($emails[$k]);
    }
  }

  return $emails;
}

/**
 * This function returns the last email as a string, the last email fron the log file
 *
 * @return String
 */
function getLastMail() {

  return getLastElement(getMailsFromLog());
}

/** This function parses an email and return the To, Subject and Body */
function parseEmail($email) {

  /** Make the email an array */
  $emailArray = explode("\n", $email);

  $result = null;

  if(!empty($emailArray)) {
    /** Start at the begging of the array */
    $i = 0;

    /** Look for the line we have the to */
    while(strpos($emailArray[$i++], "*** To:") !== 0 && array_key_exists($i, $emailArray));

    /** Parse the To: */
    preg_match("/\*\*\* To: (.*)/", $emailArray[--$i], $matches);
    $result['to'] = $matches[1];

    /** Look for the line we have subject */
    while(strpos($emailArray[$i++], "*** Subject:") !== 0 && array_key_exists($i, $emailArray));

    /** Parse the subject */
    preg_match("/\*\*\* Subject: (.*)/", $emailArray[--$i], $matches);
    $result['subject'] = $matches[1];

    /** Parse the body */
    $i = $i + 2;
    $result['body'] = "";
    while(array_key_exists($i, $emailArray)) {
      $result['body'] .= $emailArray[$i++] . "\n";
    }
  }

  return $result;
}

?>