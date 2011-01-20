<?php

/**
 * Technogate
 *
 * @package AppService
 * @author Wael Nasreddine (TechnoGate) <wael@technogate.fr>
 */
class App {

  /**
   * Get an application property
   *
   * @param $propertyName
   * @param $default
   * @return string
   */
  public static function getProperty($propertyName, $default = null) {

    /** Get Zend_Registry instance */
    $registry = Zend_Registry::getInstance();

    // Load the config
    $config = $registry->get('config');

    // Convert the configs into an array
    $configArray = $config->toArray();

    // Return the required propertyName
    $propertyNameArray = explode('.', $propertyName);
    $propertyValue = $configArray;
    for($i=0; $i < count($propertyNameArray); $i++) {
      if(array_key_exists($propertyNameArray[$i], $propertyValue)) {
        $propertyValue = $propertyValue[$propertyNameArray[$i]];
      } else {
        $propertyValue = $default;
        break;
      }
    }

    return $propertyValue;
  }
}