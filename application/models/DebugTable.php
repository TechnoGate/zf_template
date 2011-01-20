<?php

/**
 * DebugTable
 *
 * @package    Debug_Table
 * @author     Wael Nasreddine (TechnoGate) <wael@technogate.fr>
 */
class DebugTable extends Doctrine_Table {

  /**
   * Returns an instance of this class.
   *
   * @return object DebugTable
   */
  public static function getInstance() {

    return Doctrine_Core::getTable('Debugs');
  }
}

?>