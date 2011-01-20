<?php

class ZendX_ActiveRecord_Application_Resource_ActiveRecord extends Zend_Application_Resource_ResourceAbstract {

  protected $_connections = array();

  protected $_default_connection;

  protected $_model_directory;

  protected $_cfg;

  public function init() {

    if ($options = $this->getOptions()) {

      if (isset($options['model_directory']))
        $this->_getModelDirectory($options['model_directory']);

      if (isset($options['connections']))
        $this->_getConnections($options['connections']);

      if(isset($options['default_connection']) && !empty($this->_connections[$options['default_connection']]))
        $this->_getDefaultConnection($options['default_connection']);

      if (isset($options['debug']))
        $this->_getDebug($options['debug']);
    }

    /** Init ActiveRecord */
    require_once 'ActiveRecord.php';
    $this->_cfg = ActiveRecord\Config::instance();
    $this->_cfg->set_model_directory($this->_model_directory);
    $this->_cfg->set_connections($this->_connections);
    $this->_cfg->set_default_connection($this->_default_connection);

  }

  protected function _getModelDirectory($dir) {

    $this->_model_directory = $dir;
  }

  protected function _getDefaultConnection($conn) {

    $this->_default_connection = $conn;
  }

  /**
   * Lazy load connections
   *
   * @param array $connections
   * @return ZendX_Activerecord_Application_Resource_Activerecord
   *
   * @todo Handle event listeners
   */
  protected function _getConnections(array $connections = array()) {

    foreach ($connections as $name => $params) {
      if (!isset($params['dsn'])) {
        /** @see Zend_Application_Resource_ResourceAbstract */
        require_once 'ZendX/Activerecord/Application/Resource/Exception.php';

        throw new
          ZendX_Activerecord_Application_Resource_Exception('Activerecord resource dsn not present.');
      }

      $dsn = null;
      if (is_string($params['dsn'])) {
        $dsn = $params['dsn'];
      } elseif (is_array($params['dsn'])) {
        $dsn = $this->_buildConnectionString($params['dsn']);
      } else {
        /** @see Zend_Application_Resource_ResourceAbstract */
        require_once 'ZendX/Activerecord/Application/Resource/Exception.php';

        throw new
          ZendX_Activerecord_Application_Resource_Exception("Invalid
          Activerecord resource dsn format.");
      }

      $this->_connections[$name] = $dsn;
    }

    return $this;
  }

  /**
   * Build connection string
   *
   * @param  array $dsnData
   * @return string
   */
  protected function _buildConnectionString(array $dsnData = array()) {

    $connectionOptions = null;
    if ((isset($dsnData['options'])) || (!empty($dsnData['options']))) {
      $connectionOptions =
        $this->_buildConnectionOptionsString($dsnData['options']);
    }

    return sprintf('%s://%s:%s@%s/%s?%s',
      $dsnData['adapter'],
      $dsnData['user'],
      $dsnData['pass'],
      $dsnData['hostspec'],
      $dsnData['database'],
      $connectionOptions);
  }

  /**
   * Build connection options string
   *
   * @param  array $optionsData
   * @return string
   */
  protected function _buildConnectionOptionsString(array $optionsData = array())
  {
    $i = 1;
    $count = count($optionsData);
    $options  = null;

    foreach ($optionsData as $key => $value) {
      if ($i == $count) {
        $options .= "$key=$value";
      } else {
        $options .= "$key=$value&";
      }

      $i++;
    }

    return $options;
  }
}