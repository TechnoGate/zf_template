<?php

/**
 * Base_Debug
 *
 * @property integer $id
 * @property string $type
 * @property string $desc
 * @property timestamp $created_at
 * @property timestamp $updated_at
 *
 * @package    Base_Debug
 * @author     Wael Nasreddine (TechnoGate) <wael@technogate.fr>
 */
abstract class Base_Debug extends Doctrine_Record {

  const TYPE_ERROR = 'ERROR';

  // Definition
  public function setTableDefinition() {

    $this->setTableName('debugs');
    $this->hasColumn('id', 'integer', 4, array(
      'type' => 'integer',
      'length' => 4,
      'fixed' => false,
      'unsigned' => false,
      'primary' => true,
      'autoincrement' => true,
    ));
    $this->hasColumn('type', 'string', 255, array(
      'type' => 'string',
      'length' => 255,
      'fixed' => false,
      'unsigned' => false,
      'primary' => false,
      'notnull' => true,
      'autoincrement' => false,
    ));
    $this->hasColumn('desc', 'string', null, array(
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