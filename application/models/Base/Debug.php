<?php

class Base_Debug extends ActiveRecord\Model {

  const TYPE_ERROR = 'ERROR';

  // Validations
  static $validates_presence_of = array(
    array('type'),
  );

}

?>