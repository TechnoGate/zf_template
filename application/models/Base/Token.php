<?php

/**
 * Base_Token
 *
 * @property integer $id
 * @property string $hash
 * @property integer $thing_id
 * @property string $thing_type
 * @property string $action
 * @property integer $used
 * @property integer $auto_delete
 * @property integer $status
 * @property timestamp $created_at
 * @property timestamp $updated_at
 *
 * @package    Base_Debug
 * @author     Wael Nasreddine (TechnoGate) <wael@technogate.fr>
 */
class Base_Token extends Doctrine_Record {

  // Definition
  public function setTableDefinition() {

    $this->setTableName('tokens');
    $this->hasColumn('id', 'integer', 4, array(
      'type' => 'integer',
      'length' => 4,
      'fixed' => false,
      'unsigned' => false,
      'primary' => true,
      'autoincrement' => true,
    ));
    $this->hasColumn('hash', 'string', 40, array(
      'type' => 'string',
      'length' => 40,
      'fixed' => false,
      'unsigned' => false,
      'primary' => false,
      'notnull' => true,
      'autoincrement' => false,
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
    $this->hasColumn('action', 'string', 255, array(
      'type' => 'string',
      'length' => 255,
      'fixed' => false,
      'unsigned' => false,
      'primary' => false,
      'notnull' => false,
      'autoincrement' => false,
    ));
    $this->hasColumn('used', 'integer', 4, array(
      'type' => 'integer',
      'length' => 4,
      'fixed' => false,
      'unsigned' => false,
      'primary' => false,
      'notnull' => false,
      'autoincrement' => false,
    ));
    $this->hasColumn('auto_delete', 'integer', 1, array(
      'type' => 'integer',
      'length' => 1,
      'fixed' => false,
      'unsigned' => false,
      'primary' => false,
      'notnull' => false,
      'autoincrement' => false,
    ));
    $this->hasColumn('status', 'integer', 2, array(
      'type' => 'integer',
      'length' => 2,
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