<?php

/**
 * Base_Log
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $session_cookie
 * @property string $level
 * @property string $ip
 * @property string $name
 * @property string $p1
 * @property string $p2
 * @property string $p3
 * @property string $p4
 * @property timestamp $created_at
 * @property timestamp $updated_at
 *
 * @package    Base_Debug
 * @author     Wael Nasreddine (TechnoGate) <wael@technogate.fr>
 */
abstract class Base_Log extends Doctrine_Record {

  const ANALYTICS_TYPE_INFO = 'INFO';
  const ANALYTICS_NAME_SEARCH = 'SEARCH';
  const ANALYTICS_NAME_FEED = 'FEED';
  const ANALYTICS_NAME_CHANGEIDENTITY = 'CHANGE_IDENTITY';

  // Definition
  public function setTableDefinition() {

    $this->setTableName('logs');
    $this->hasColumn('id', 'integer', 4, array(
      'type' => 'integer',
      'length' => 4,
      'fixed' => false,
      'unsigned' => false,
      'primary' => true,
      'autoincrement' => true,
    ));
    $this->hasColumn('user_id', 'integer', 4, array(
      'type' => 'integer',
      'length' => 4,
      'fixed' => false,
      'unsigned' => false,
      'primary' => false,
      'notnull' => false,
      'autoincrement' => false,
    ));
    $this->hasColumn('session_cookie', 'string', 255, array(
      'type' => 'string',
      'length' => 255,
      'fixed' => false,
      'unsigned' => false,
      'primary' => false,
      'notnull' => false,
      'autoincrement' => false,
    ));
    $this->hasColumn('level', 'string', 128, array(
      'type' => 'string',
      'length' => 128,
      'fixed' => false,
      'unsigned' => false,
      'primary' => false,
      'notnull' => true,
      'autoincrement' => false,
    ));
    $this->hasColumn('ip', 'string', 20, array(
      'type' => 'string',
      'length' => 20,
      'fixed' => false,
      'unsigned' => false,
      'primary' => false,
      'notnull' => false,
      'autoincrement' => false,
    ));
    $this->hasColumn('name', 'string', 128, array(
      'type' => 'string',
      'length' => 128,
      'fixed' => false,
      'unsigned' => false,
      'primary' => false,
      'notnull' => true,
      'autoincrement' => false,
    ));
    $this->hasColumn('p1', 'string', 255, array(
      'type' => 'string',
      'length' => 255,
      'fixed' => false,
      'unsigned' => false,
      'primary' => false,
      'notnull' => false,
      'autoincrement' => false,
    ));
    $this->hasColumn('p2', 'string', 255, array(
      'type' => 'string',
      'length' => 255,
      'fixed' => false,
      'unsigned' => false,
      'primary' => false,
      'notnull' => false,
      'autoincrement' => false,
    ));
    $this->hasColumn('p3', 'string', 255, array(
      'type' => 'string',
      'length' => 255,
      'fixed' => false,
      'unsigned' => false,
      'primary' => false,
      'notnull' => false,
      'autoincrement' => false,
    ));
    $this->hasColumn('p4', 'string', 255, array(
      'type' => 'string',
      'length' => 255,
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