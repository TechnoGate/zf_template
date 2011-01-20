<?php

class Event extends ActiveRecord\Model {

  // Associations
  static $belongs_to = array(
    array('parent', 'class_name' => 'Event'),
  );
}

?>