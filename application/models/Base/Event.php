<?php

/*
 * Base_Event
 *
 * @property integer $id
 * @property integer $thing_id
 * @property string $thing_type
 * @property string $name
 * @property string $type
 * @property integer $parent_id
 * @property string $parameters
 * @property timestamp $created_at
 * @property timestamp $updated_at
 *
 * @package    Base_Debug
 * @author     Wael Nasreddine (TechnoGate) <wael@technogate.fr>
 */
abstract class Base_Event extends Doctrine_Record {

  // Definition
  public function setTableDefinition() {

    $this->setTableName('events');
    $this->hasColumn('id', 'integer', 4, array(
      'type' => 'integer',
      'length' => 4,
      'fixed' => false,
      'unsigned' => false,
      'primary' => true,
      'autoincrement' => true,
    ));
    $this->hasColumn('thing_id', 'integer', 4, array(
      'type' => 'integer',
      'length' => 4,
      'fixed' => false,
      'unsigned' => false,
      'primary' => false,
      'notnull' => true,
      'autoincrement' => false,
    ));
    $this->hasColumn('thing_type', 'string', 16, array(
      'type' => 'string',
      'length' => 16,
      'fixed' => false,
      'unsigned' => false,
      'primary' => false,
      'notnull' => true,
      'autoincrement' => false,
    ));
    $this->hasColumn('name', 'string', 45, array(
      'type' => 'string',
      'length' => 45,
      'fixed' => false,
      'unsigned' => false,
      'primary' => false,
      'notnull' => false,
      'autoincrement' => false,
    ));
    $this->hasColumn('type', 'string', 45, array(
      'type' => 'string',
      'length' => 45,
      'fixed' => false,
      'unsigned' => false,
      'primary' => false,
      'notnull' => false,
      'autoincrement' => false,
    ));
    $this->hasColumn('parent_id', 'integer', 4, array(
      'type' => 'integer',
      'length' => 4,
      'fixed' => false,
      'unsigned' => false,
      'primary' => false,
      'notnull' => false,
      'autoincrement' => false,
    ));
    $this->hasColumn('parameters', 'string', null, array(
      'type' => 'string',
      'fixed' => false,
      'unsigned' => false,
      'primary' => false,
      'notnull' => false,
      'autoincrement' => false,
    ));
    $this->hasColumn('created_at', 'timestamp', null, array(
      'type' => 'timestamp',
      'fixed' => false,
      'unsigned' => false,
      'primary' => false,
      'notnull' => false,
      'autoincrement' => false,
    ));
    $this->hasColumn('updated_at', 'timestamp', null, array(
      'type' => 'timestamp',
      'fixed' => false,
      'unsigned' => false,
      'primary' => false,
      'notnull' => false,
      'autoincrement' => false,
    ));
  }

  // Setup
  public function setUp() {

    parent::setUp();
  }
}

?>