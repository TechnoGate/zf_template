<?php
/**
 * Surl.php
 *
 * Copyright (c) 2010, Wael Nasreddine (TechnoGate) <wael@technogate.fr>
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *     * Redistributions of source code must retain the above copyright
 *       notice, this list of conditions and the following disclaimer.
 *     * Redistributions in binary form must reproduce the above copyright
 *       notice, this list of conditions and the following disclaimer in the
 *       documentation and/or other materials provided with the distribution.
 *     * Neither the name of the <organization> nor the
 *       names of its contributors may be used to endorse or promote products
 *       derived from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY MAURICE FONK ''AS IS'' AND ANY
 * EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
 * WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 * DISCLAIMED. IN NO EVENT SHALL <copyright holder> BE LIABLE FOR ANY
 * DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
 * (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
 * ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
 * SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * @category   Technogate
 * @package    Technogate_View_Helper
 * @copyright  Copyright (c) 2010 Wael Nasreddine (TechnoGate) <wael@technogate.fr>
 * @version    0.1
 */

/** Zend_View_Helper_Abstract.php */
require_once 'Zend/View/Helper/Abstract.php';

/** Zend_View_Helper_Url.php */
require_once 'Zend/View/Helper/Url.php';

/** Zend_Controller_Front */
require_once 'Zend/Controller/Front.php';

/**
 * Helper for making easy links, using hash as seperator
 *
 * @package    Zend_View
 * @subpackage Helper
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Technogate_View_Helper_Surl extends Zend_View_Helper_Abstract {

  public function surl($url, array $params = array(), $reset = true, $params_get = false, $encode = true, $name = null, $public_path = null) {

    /** Define the public_path if needed */
    if($public_path === null && defined('PUBLIC_PATH'))
      $public_path = PUBLIC_PATH;

    /** Get the base url */
    $baseUrl = Zend_Controller_Front::getInstance()->getBaseUrl();

    /** Get the url_helper */
    $url_helper = new Zend_View_Helper_Url();

    /** Are the params a GET ? */
    if($params_get === true) {
      $params_get_string = '?';
      $sep = '';
      foreach($params as $k => $v) {
        if(is_array($v)) {
          foreach($v as $v2) {
            $params_get_string .= $sep . $k . '[]=' . $v2;
            $sep = '&';
          }
        } else {
          $params_get_string .= $sep . $k . '=' . $v;
          $sep = '&';
        }
      }
      $params = array();
    } else {
      $params_get_string = '';
    }

    /** Get an url without params */
    $url_without_params = explode('?', $url);
    $url_without_params = $url_without_params[0];

    /** check if the url is a static file */
    if(!empty($url) && !empty($url_without_params) && file_exists($public_path . '/' . $url_without_params)) {
      $result = $baseUrl . '/' . $url;
    } else if(strStartsWith($url, ':')) { // Check if it's a router
      $name = substr($url, 1, strlen($url) - 1);
      $result = $url_helper->url($params, $name, $reset, $encode);
    } else if(strpos($url, 'www.') === 0) { // Check if the link is a partial external link
      $result = 'http://' . $url;
    } else if(strpos($url, 'http://') === 0 || strpos($url, 'https://') === 0) { // Check if the link is an external link
      $result = $url;
    } else {
      /** Parse the url */
      $url_helper_options = $this->_parseUrl($url);

      /** Use the default router if non given and reset is requested */
      if($name === null && $reset === true)
        $name = 'default';

      /** Forward the request to the url helper */
      $result = $url_helper->url(array_merge($url_helper_options, $params), $name, $reset, $encode);
    }

    return $result . $params_get_string;
  }

  protected function _parseUrl($url) {

    /** Get the request */
    $request = Zend_Controller_Front::getInstance()->getRequest();

    if(empty($url)) {
      $url_helper_options = array();
    } else {
      /** Split the url on the # */
      $a_url = explode('#', $url);

      /** Fill the module, controller and action */
      switch (count($a_url)) {
      case 3:
        $module = $a_url[0];
        $controller = $a_url[1];
        $action = $a_url[2];
        break;
      case 2:
        $module = $request->getModuleName();
        $controller = $a_url[0];
        $action = $a_url[1];
        break;
      case 1:
        $module = $request->getModuleName();
        $controller = $request->getControllerName();
        $action = $a_url[0];
        break;
      default:
        require_once 'Technogate/View/Helper/Surl/Exception.php';
        throw new Technogate_View_Helper_Surl_Exception("Cannot parse the url");
      }

      /** Construct the url helper options */
      $url_helper_options = array(
        'module'      => $module,
        'controller'  => $controller,
        'action'      => $action,
      );
    }

    return $url_helper_options;
  }
}