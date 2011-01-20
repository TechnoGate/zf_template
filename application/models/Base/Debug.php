<?php

class Base_Debug extends ActiveRecord\Model {

  // Validations
  static $validates_presence_of = array(
    array('type'),
  );

}

?>