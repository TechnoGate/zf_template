<?php

/**
 * BaseTable
 *
 * @package    Base_Table
 * @author     Wael Nasreddine (TechnoGate) <wael@technogate.fr>
 */
class BaseTable extends Doctrine_Table {

  /**
   * Returns an instance of this class.
   *
   * @return object BaseTable
   */
  public static function getInstance() {

    return Doctrine_Core::getTable('Bases');
  }
}

?>