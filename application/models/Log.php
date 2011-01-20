<?php

class Log extends ActiveRecord\Model {

  const ANALYTICS_TYPE_INFO = 'INFO';
  const ANALYTICS_NAME_SEARCH = 'SEARCH';
  const ANALYTICS_NAME_FEED = 'FEED';
  const ANALYTICS_NAME_CHANGEIDENTITY = 'CHANGE_IDENTITY';


  // Validations
  static $validates_presence_of = array(
    array('level'),
    array('ip'),
    array('name'),
  );

  // Associations
  static $belongs_to = array(
    array('user'),
  );

  public static function logEvent($name, $p1='', $p2='', $p3='', $p4='') {

    $userId = Session::getLoggedInUserId();

    if(array_key_exists('TechnogateX', $_COOKIE) && !empty($_COOKIE['TechnogateX'])) {
      $sessionCookie = $_COOKIE['TechnogateX'] ? $_COOKIE['TechnogateX'] : 'empty';
    } else {
      $sessionCookie = 'empty';
    }

    // Check if the logEvent has been called from a browser
    // or from command line! if it's from command line, several
    // global variables won't be defined!
    if(empty($_SERVER['REMOTE_ADDR'])) {
      $ip = "0.0.0.0";
      $session_cookie = "empty";
      $server_name = "php-cgi";
    } else {
      $ip = $_SERVER['REMOTE_ADDR'];
      $session_cookie = $_COOKIE['PHPSESSID'];
      $server_name = $_SERVER['SERVER_NAME'];
    }


    Log::create(array(
      'level' => self::ANALYTICS_TYPE_INFO,
      'name' => $name,
      'p1' => $p1,
      'p2' => $p2,
      'p3' => $p3,
      'p4' => $p4,
      'ip' => $ip,
      'user_id' => $userId,
      'session_cookie' => $session_cookie,
    ));
  }
}

?>