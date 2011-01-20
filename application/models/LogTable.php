<?php

/**
 * LogTable
 *
 * @package    Log_Table
 * @author     Wael Nasreddine (TechnoGate) <wael@technogate.fr>
 */
class LogTable extends Doctrine_Table {

  /**
   * Returns an instance of this class.
   *
   * @return object LogTable
   */
  public static function getInstance() {

    return Doctrine_Core::getTable('Logs');
  }
}

?>