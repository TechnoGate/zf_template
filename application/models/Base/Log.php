<?php

class Base_Log extends ActiveRecord\Model {

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

}

?>