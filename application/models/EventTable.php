<?php

/**
 * EventTable
 *
 * @package    Event_Table
 * @author     Wael Nasreddine (TechnoGate) <wael@technogate.fr>
 */
class EventTable extends Doctrine_Table {

  /**
   * Returns an instance of this class.
   *
   * @return object EventTable
   */
  public static function getInstance() {

    return Doctrine_Core::getTable('Events');
  }
}

?>