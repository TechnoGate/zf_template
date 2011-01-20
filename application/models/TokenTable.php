<?php

/**
 * TokenTable
 *
 * @package    Token_Table
 * @author     Wael Nasreddine (TechnoGate) <wael@technogate.fr>
 */
class TokenTable extends Doctrine_Table {

  /**
   * Returns an instance of this class.
   *
   * @return object TokenTable
   */
  public static function getInstance() {

    return Doctrine_Core::getTable('Tokens');
  }
}

?>