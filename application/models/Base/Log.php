<?php

class Base_Log extends ActiveRecord\Model {

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

}

?>