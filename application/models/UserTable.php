<?php

/**
 * UserTable
 *
 * @package    User_Table
 * @author     Wael Nasreddine (TechnoGate) <wael@technogate.fr>
 */
class UserTable extends Doctrine_Table {

  /**
   * Returns an instance of this class.
   *
   * @return object UserTable
   */
  public static function getInstance() {

    return Doctrine_Core::getTable('Users');
  }
}

?>