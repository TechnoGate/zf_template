<?php

/**
 * Base_User
 *
 * @property integer $id
 * @property string $login
 * @property string $name
 * @property string $email
 * @property string $hashed_password
 * @property string $phone
 * @property string $profile_picture
 * @property string $status
 * @property integer $blocked
 * @property timestamp $last_login_at
 * @property timestamp $created_at
 * @property timestamp $updated_at
 *
 * @package    Base_Debug
 * @author     Wael Nasreddine (TechnoGate) <wael@technogate.fr>
 */
class Base_User extends Doctrine_Record {

  const WAITACTIVATION = "WAITACTIVATION";

  const COMPLETED = "COMPLETED";

  const USER_MIN_LENGTH = 4;

  const USER_MAX_LENGTH = 20;

  const PASS_MIN_LENGTH = 6;

  const PASS_MAX_LENGTH = 20;

  // Definition
  public function setTableDefinition() {

    $this->setTableName('users');
    $this->hasColumn('id', 'integer', 4, array(
      'type' => 'integer',
      'length' => 4,
      'fixed' => false,
      'unsigned' => false,
      'primary' => true,
      'autoincrement' => true,
    ));
    $this->hasColumn('login', 'string', 30, array(
      'type' => 'string',
      'length' => 30,
      'fixed' => false,
      'unsigned' => false,
      'primary' => false,
      'notnull' => true,
      'autoincrement' => false,
    ));
    $this->hasColumn('name', 'string', 255, array(
      'type' => 'string',
      'length' => 255,
      'fixed' => false,
      'unsigned' => false,
      'primary' => false,
      'notnull' => true,
      'autoincrement' => false,
    ));
    $this->hasColumn('email', 'string', 255, array(
      'type' => 'string',
      'length' => 255,
      'fixed' => false,
      'unsigned' => false,
      'primary' => false,
      'notnull' => true,
      'autoincrement' => false,
    ));
    $this->hasColumn('hashed_password', 'string', 255, array(
      'type' => 'string',
      'length' => 255,
      'fixed' => false,
      'unsigned' => false,
      'primary' => false,
      'notnull' => true,
      'autoincrement' => false,
    ));
    $this->hasColumn('phone', 'string', 45, array(
      'type' => 'string',
      'length' => 45,
      'fixed' => false,
      'unsigned' => false,
      'primary' => false,
      'notnull' => false,
      'autoincrement' => false,
    ));
    $this->hasColumn('profile_picture', 'string', 255, array(
      'type' => 'string',
      'length' => 255,
      'fixed' => false,
      'unsigned' => false,
      'primary' => false,
      'notnull' => false,
      'autoincrement' => false,
    ));
    $this->hasColumn('status', 'string', 45, array(
      'type' => 'string',
      'length' => 45,
      'fixed' => false,
      'unsigned' => false,
      'primary' => false,
      'notnull' => false,
      'autoincrement' => false,
    ));
    $this->hasColumn('blocked', 'integer', 1, array(
      'type' => 'integer',
      'length' => 1,
      'fixed' => false,
      'unsigned' => false,
      'primary' => false,
      'default' => '0',
      'notnull' => true,
      'autoincrement' => false,
    ));
    $this->hasColumn('last_login_at', 'timestamp', null, array(
      'type' => 'timestamp',
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