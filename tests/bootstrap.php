<?php

// Define path to root directory
if (! defined('ROOT_PATH'))
  define('ROOT_PATH', realpath(dirname(__FILE__) . '/..'));

// Define path to application directory
if (! defined('APPLICATION_PATH'))
  define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

// Define path to public directory
if (! defined('PUBLIC_PATH'))
  define('PUBLIC_PATH', realpath(ROOT_PATH . '/public'));

// Let the application know this are the tests
if (!defined('SIMPLETEST'))
  define('SIMPLETEST', true);

// Define Bypass Session
if (! defined('BYPASS_SESSION'))
  define('BYPASS_SESSION', true);

// Set include path
set_include_path(APPLICATION_PATH
  . PATH_SEPARATOR . realpath(ROOT_PATH . '/library')
  . PATH_SEPARATOR . realpath(ROOT_PATH . '/library/Zend/library')
  . PATH_SEPARATOR . realpath(ROOT_PATH . '/library/php-activerecord')
  . PATH_SEPARATOR . realpath(APPLICATION_PATH . '/models/')
  . PATH_SEPARATOR . realpath(APPLICATION_PATH . '/models/Exceptions')
  . PATH_SEPARATOR . realpath(APPLICATION_PATH . '/forms/')
  . PATH_SEPARATOR . get_include_path());

// Include Zend auto loader
require_once 'Zend/Loader.php';
Zend_Loader::loadFile('Zend/Loader/Autoloader.php');
$loader = Zend_Loader_Autoloader::getInstance();
$loader->registerNamespace('Technogate_');
$loader->setFallbackAutoloader(true);

// Load the Common file.
Zend_Loader::loadFile('Technogate/Common.php');
Zend_Loader::loadFile('Technogate/Test/TestRunner.php');
Zend_Loader::loadFile('Simpletest/autorun.php');

// registry
$registry = Zend_Registry::getInstance();

// Uses the layout feature
$layoutParams = array('layoutPath' =>  APPLICATION_PATH . '/views/layouts');
Zend_Layout::startMvc($layoutParams);

// We should run as localhost.
define ('HTTP_SERVER_PATH',"http://localhost");
$domain="simpletest";

// Load the config.
try{
  $configFile = realpath(APPLICATION_PATH . '/configs/application.ini');
  $config = new Zend_Config_Ini($configFile,'production-scripts');
  Zend_Registry::set('config', $config);
} catch(Zend_Config_Exception $e){
  echo getCurrentDate() . " Error opening the config file : " . $e->getMessage();
  $trace = $e->getTrace();
  $traceStr = '';
  foreach ($trace as $tr){
    $traceStr .= $tr['file']." (".$tr['line']. ") <strong>" .$tr['class']."->".$tr['function']."</strong>(";
    $traceStr .= ')<br/>';
  }
  echo $traceStr;
} catch(Exception $e) {
  echo getCurrentDate() . " Unknow Error opening the config file : " . $e->getMessage();
}

// setup database
$dbAdapter = Zend_Db::factory($config->resources->db->adapter,$config->resources->db->params->toArray());
$dbAdapter->query("SET NAMES 'utf8'");
Zend_Db_Table::setDefaultAdapter($dbAdapter);
Zend_Registry::set('dbAdapter', $dbAdapter);

// session start
Zend_Session::start();

// Setup the locale
$locale = "fr_FR";

//$locale = "en_EN";
putenv("LC_ALL=$locale");
setlocale(LC_ALL, $locale);
putenv('LANG='.$locale);
putenv('LANGUAGE='.$locale);
bindtextdomain("translation", APPLICATION_PATH . "/../data/locales");
bind_textdomain_codeset("translation", "UTF-8");
textdomain("translation");
Zend_Registry::set('locale', $locale);

/** Initialize Zend_Translate */
$translate = new Zend_Translate('gettext', APPLICATION_PATH . "/../data/locales", $locale, array('scan' => 'directory'));
$registry->set('Zend_Translate', $translate);

// Define Firebug as a Null writer, Firebug doesn't work
// If you got an Exception like:
// Unexpected exception of type [Zend_Wildfire_Exception] with message
//	[Response objects not initialized.] in [Zend/Wildfire/Channel/HttpHeaders.php line 285]
// That means that you have enabled FireBug.. Don't!
$loggerFb = new Zend_Log();
$writer = new Zend_Log_Writer_Null();
$loggerFb->addWriter($writer);
Zend_Registry::set('fphpLogger',$loggerFb);

?>