<?php

/**
 * @see Zend_Auth_Adapter_Interface
 */
require_once 'Zend/Auth/Adapter/Interface.php';

/**
 * @see Zend_Db_Adapter_Abstract
 */
require_once 'Zend/Db/Adapter/Abstract.php';

/**
 * @see Zend_Auth_Result
 */
require_once 'Zend/Auth/Result.php';

/**
 * @category   ZendX
 * @package    ZendX_Auth
 * @subpackage Adapter
 * @copyright  Copyright (c) 2011 Wael Nasreddine (TechnoGate) <wael.nasreddine@gmail.com>
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class ZendX_Auth_Adapter_Activerecord implements Zend_Auth_Adapter_Interface
{

  /**
   * ZEND_AUTH_CREDENTIAL_MATCH - The field name we'd use in SQL to store the credential match
   *
   * @var string
   */
  const ZEND_AUTH_CREDENTIAL_MATCH = 'zend_auth_credential_match';

  /**
   * $_model - The model name to check
   *
   * @var string
   */
  protected $_model = null;

  /**
   * $_connection - The ActiveRecord connection instance
   *
   * @var ActiveRecord\Connection
   */
  protected $_connection = null;

  /**
   * $_conditions - The conditions which we will be building
   *
   * @var string
   */
  protected $_conditions = null;

  /**
   * $_dbSelect - The SELECT which we will be building
   *
   * @var string
   */
  protected $_dbSelect = null;

  /**
   * $_tableName - the table name to check
   *
   * @var string
   */
  protected $_tableName = null;

  /**
   * $_identityColumn - the column to use as the identity
   *
   * @var string
   */
  protected $_identityColumn = null;

  /**
   * $_credentialColumns - columns to be used as the credentials
   *
   * @var string
   */
  protected $_credentialColumn = null;

  /**
   * $_identity - Identity value
   *
   * @var string
   */
  protected $_identity = null;

  /**
   * $_credential - Credential values
   *
   * @var string
   */
  protected $_credential = null;

  /**
   * $_authenticateResultInfo
   *
   * @var array
   */
  protected $_authenticateResultInfo = null;

  /**
   * $_resultRow - Results of database authentication query
   *
   * @var array
   */
  protected $_resultRow = null;

  /**
   * $_credentialTreatment - Treatment applied to the credential, such as MD5() or PASSWORD()
   *
   * @var string
   */
  protected $_credentialTreatment = null;

  /**
   * __construct() - Sets configuration options
   *
   * @param  string  $model
   * @param  string  $identityColumn
   * @param  string  $credentialColumn
   * @param  string  $credentialTreatment
   * @return void
   */
  public function __construct($identityColumn = null, $credentialColumn = null, $credentialTreatment = null) {

    $this->_connection = ActiveRecord\Connection::instance();

    if (null !== $identityColumn) {
      $this->setIdentityColumn($identityColumn);
    }

    if (null !== $credentialColumn) {
      $this->setCredentialColumn($credentialColumn);
    }

    if (null !== $credentialTreatment) {
      $this->setCredentialTreatment($credentialTreatment);
    }
  }

  /**
   * setModel() - set the model to be used in the select query
   *
   * @param  string $model
   * @return ZendX_Auth_Adapter_ActiveRecord Provides a fluent interface
   */
  public function setModel($model) {

    $this->_model = $model;
    return $this;
  }

  /**
   * setCredentialTreatment() - allows the developer to pass a parameterized string that is
   * used to transform or treat the input credential data.
   *
   * In many cases, passwords and other sensitive data are encrypted, hashed, encoded,
   * obscured, or otherwise treated through some function or algorithm. By specifying a
   * parameterized treatment string with this method, a developer may apply arbitrary SQL
   * upon input credential data.
   *
   * Examples:
   *
   *  'PASSWORD(?)'
   *  'MD5(?)'
   *
   * @param  string $treatment
   * @return ZendX_Auth_Adapter_ActiveRecord Provides a fluent interface
   */
  public function setCredentialTreatment($credentialTreatment) {

    $this->_credentialTreatment = $credentialTreatment;
    return $this;
  }

  /**
   * setIdentityColumn() - set the column name to be used as the identity column
   *
   * @param  string $identityColumn
   * @return ZendX_Auth_Adapter_ActiveRecord Provides a fluent interface
   */
  public function setIdentityColumn($identityColumn) {

    $this->_identityColumn = $identityColumn;
    return $this;
  }

  /**
   * setCredentialColumn() - set the column name to be used as the credential column
   *
   * @param  string $credentialColumn
   * @return ZendX_Auth_Adapter_ActiveRecord Provides a fluent interface
   */
  public function setCredentialColumn($credentialColumn) {

    $this->_credentialColumn = $credentialColumn;
    return $this;
  }

  /**
   * setIdentity() - set the value to be used as the identity
   *
   * @param  string $value
   * @return ZendX_Auth_Adapter_ActiveRecord Provides a fluent interface
   */
  public function setIdentity($value) {

    $this->_identity = $value;
    return $this;
  }

  /**
   * setCredential() - set the credential value to be used, optionally can specify a treatment
   * to be used, should be supplied in parameterized form, such as 'MD5(?)' or 'PASSWORD(?)'
   *
   * @param  string $credential
   * @return ZendX_Auth_Adapter_ActiveRecord Provides a fluent interface
   */
  public function setCredential($credential) {

    $this->_credential = $credential;
    return $this;
  }

  /**
   * getResultRowObject() - Returns the result row as a stdClass object
   *
   * @param  string|array $returnColumns
   * @param  string|array $omitColumns
   * @return stdClass|boolean
   */
  public function getResultRowObject($returnColumns = null, $omitColumns = null) {

    if (!$this->_resultRow) {
      return false;
    }

    $returnObject = new stdClass();

    if (null !== $returnColumns) {

      $availableColumns = array_keys($this->_resultRow);
      foreach ( (array) $returnColumns as $returnColumn) {
        if (in_array($returnColumn, $availableColumns)) {
          $returnObject->{$returnColumn} = $this->_resultRow[$returnColumn];
        }
      }
      return $returnObject;

    } elseif (null !== $omitColumns) {

      $omitColumns = (array) $omitColumns;
      foreach ($this->_resultRow as $resultColumn => $resultValue) {
        if (!in_array($resultColumn, $omitColumns)) {
          $returnObject->{$resultColumn} = $resultValue;
        }
      }
      return $returnObject;

    } else {

      foreach ($this->_resultRow as $resultColumn => $resultValue) {
        $returnObject->{$resultColumn} = $resultValue;
      }
      return $returnObject;

    }
  }

  /**
   * authenticate() - defined by Zend_Auth_Adapter_Interface.  This method is called to
   * attempt an authentication.  Previous to this call, this adapter would have already
   * been configured with all necessary information to successfully connect to a database
   * table and attempt to find a record matching the provided identity.
   *
   * @throws Zend_Auth_Adapter_Exception if answering the authentication query is impossible
   * @return Zend_Auth_Result
   */
  public function authenticate() {

    // Get the tableName from the model
    $this->_getTableName();

    // Build up the conditions
    $this->_buildConditions();

    // Build the select
    $this->_buildSelectQuery();

    // Get the result by invoking the SQL query
    $this->_getResultRow();

    // Check if credential passes
    $this->_checkCredentials();

    // Return the result
    return $this->_authenticateCreateAuthResult();
  }

  /**
   * _getTableName() - Get the table name from the model
   *
   * @return void
   */
  protected function _getTableName() {

    $this->_tableName = call_user_func($this->_model . '::table')->table;
  }

  /**
   * _buildConditions() - Build the conditions
   *
   * @return void
   */
  protected function _buildConditions() {

    // Make sure that the credentialTreatment has the ? char
    if(empty($this->_credentialTreatment) || strpos($this->_credentialTreatment, '?') === false)
      $this->_credentialTreatment = '?';

    // Quote the credential into the credentialTreatment
    $this->_credentialTreatment = $this->_quoteInto($this->_credentialTreatment, array($this->_credential));

    // Construct the first part of the condition which will have the identity injected in later
    $where = $this->_identityColumn . ' = ?';

    // Construct the conditions
    $this->_conditions = $this->_quoteInto($where, array($this->_identity));
  }

  /**
   * _buildSelectQuery() - Build the entire SQL query
   *
   * @return void
   */
  protected function _buildSelectQuery() {

    $this->_dbSelect = sprintf("SELECT %s.*, (CASE WHEN %s = %s THEN 1 ELSE 0 END) AS %s FROM %s WHERE %s LIMIT 1",
      $this->_connection->quote_name($this->_tableName),
      $this->_connection->quote_name($this->_credentialColumn),
      $this->_credentialTreatment,
      $this->_connection->quote_name(self::ZEND_AUTH_CREDENTIAL_MATCH),
      $this->_connection->quote_name($this->_tableName),
      $this->_conditions
    );
  }

  /**
   * _getResultRow() - Execute the SQL query and set the result in the object
   *
   * @return void
   */
  protected function _getResultRow() {

    $resultRow = call_user_func(array($this->_model, 'find_by_sql'), $this->_dbSelect);
    if(is_array($resultRow))
      $resultRow = getFirstElement($resultRow);

    $this->_resultRow = $resultRow->to_array();
  }

  /**
   * _checkCredentials() - Check the credentials from the resultRow and set the _authenticateResultInfo
   *
   * @return void
   */
  protected function _checkCredentials() {

    // Initialize the result
    $this->_authenticateResultInfo = array(
      'code'     => Zend_Auth_Result::FAILURE,
      'identity' => $this->_identity,
      'messages' => array()
    );

    if($this->_resultRow[self::ZEND_AUTH_CREDENTIAL_MATCH] != 1) {
      $this->_authenticateResultInfo['code'] = Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID;
      $this->_authenticateResultInfo['messages'][] = 'Supplied credential is invalid.';
    } else {
      $this->_authenticateResultInfo['code'] = Zend_Auth_Result::SUCCESS;
      $this->_authenticateResultInfo['messages'][] = 'Authentication successful.';
    }

    // Remove the credential match
    unset($this->_resultRow[self::ZEND_AUTH_CREDENTIAL_MATCH]);
  }

  /**
   * _quoteInto() - Quote values into a SQL text
   *
   * @param  string  $text
   * @param  array   $values
   * @return string
   */
  protected function _quoteInto($text, array $values) {

    // Initialize data needed by the while loop
    $count = sizeof($values);
    $i = 0;

    while ($i < $count AND $count > 0) {
      if (strpos($text, '?') !== false) {
        $text = substr_replace($text, $this->_connection->escape($values[$i]), strpos($text, '?'), 1);
      }
      $i++;
    }
    return $text;
  }

  /**
   * _authenticateCreateAuthResult() - Creates a Zend_Auth_Result object from
   * the information that has been collected during the authenticate() attempt.
   *
   * @return Zend_Auth_Result
   */
  protected function _authenticateCreateAuthResult() {

    return new Zend_Auth_Result(
      $this->_authenticateResultInfo['code'],
      $this->_authenticateResultInfo['identity'],
      $this->_authenticateResultInfo['messages']
    );
  }
}
?>