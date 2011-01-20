<?php

class Debug extends ActiveRecord\Model {

  const TYPE_ERROR = 'ERROR';

  // Validations
  static $validates_presence_of = array(
    array('type'),
  );

  public static function traceException(Exception $exception) {

    $trace = new Debug();
    $trace->type = self::TYPE_ERROR;
    $trace->desc = $_SERVER['SERVER_NAME']
      . " -- "
      . $_SERVER['SCRIPT_NAME']
      . " -- "
      . $_SERVER['REQUEST_URI']
      . " -- "
      . $_SERVER['REMOTE_ADDR']
      . " -- "
      . $_SERVER['SERVER_ADDR']
      . " -- "
      . get_class($exception)
      . " -- "
      . $exception->getCode()
      . " -- "
      . $exception->getMessage();

    $trace->save();
  }
}

?>