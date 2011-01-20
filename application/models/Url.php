<?php

class Url {

  /**
   * This function is used to get the baseUrl
   *
   * @param bool $includeServer : include the server too ?
   * @return string
   */
  public static function getBaseUrl($includeServer = false) {

    /** Get the front Controller and the request */
    $frontController = Zend_Controller_Front::getInstance();
    $request = $frontController->getRequest();

    /** Get the baseUrl */
    $baseUrl = $request->getBaseUrl();

    if ($includeServer === true) {
      $serverUrl = 'http://' . $request->getHttpHost();
    } else {
      $serverUrl = '';
    }

    return $serverUrl . $baseUrl;
  }

  /**
   * This function split up an url into module, controller, action and params
   *
   * @param string $url
   * @return array(
   * 	'controller' => string,
   * 	'action'     => string,
   * 	'params'     => array('key' => value)
   * );
   */
  public static function getUrlProprieties($url) {

    /** Split the url on / */
    $urlArray = explode('/', $url);

    /** Initialize the result */
    $result = array(
      'module' => '',
      'controller' => '',
      'action' => '',
      'params' => array(),
    );

    $result['module'] = $urlArray[0];
    $result['controller'] = $urlArray[1];
    $result['action'] = $urlArray[2];

    for ($i = 3; $i < count($urlArray); $i += 2) {
      $result['params'][$urlArray[$i]] = $urlArray[$i + 1];
    }

    return $result;
  }
}
?>