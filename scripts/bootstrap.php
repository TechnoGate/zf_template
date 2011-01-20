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
include "Zend/Loader.php";
Zend_Loader::loadFile('Zend/Loader/Autoloader.php');
$loader = Zend_Loader_Autoloader::getInstance();
$loader->registerNamespace('Technogate_');
$loader->setFallbackAutoloader(true);

// Load the Common file.
Zend_Loader::loadFile('Technogate/Common.php');

// Load the config.
try{
  $configFile = realpath(APPLICATION_PATH . '/configs/application.ini');
  $config = new Zend_Config_Ini($configFile,'production-scripts');
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
  echo getCurrentDate() . " Error fetching the config file: " . $e->getMessage();
}

/** Get an instance of Zend Registry */
$registry = Zend_Registry::getInstance();

// setup database
$dbAdapter = Zend_Db::factory($config->resources->db->adapter,$config->resources->db->params->toArray());
$dbAdapter->query("SET NAMES 'utf8'");
Zend_Db_Table::setDefaultAdapter($dbAdapter);
Zend_Registry::set('dbAdapter', $dbAdapter);
Zend_Registry::set('config', $config);

// Setup the locale
$locale = "fr_FR";

//$locale = "en_EN";
putenv("LC_ALL=$locale");
setlocale(LC_ALL, $locale);
putenv('LANG='.$locale);
putenv('LANGUAGE='.$locale);
bindtextdomain("messages", ROOT_PATH . "/locale");
bind_textdomain_codeset("messages", "UTF-8");
textdomain("messages");
Zend_Registry::set('locale', $locale);

/** Initialize Zend_Translate */
$translate = new Zend_Translate('gettext', ROOT_PATH . "/data/locales", $locale, array('scan' => 'directory'));
$registry->set('Zend_Translate', $translate);

/** Initialize cache */
$cacheBackendName = 'File';
$cacheFrontendName = 'Core';

$cacheBackendOptions = array(
  'cache_dir' => ROOT_PATH . '/.zfcache/' // Directory where to put the cache files
);

$cacheFrontendOptions = array(
  'lifetime' => 7200, // cache lifetime of 2 hours
  'automatic_serialization' => true
);

// Get Zend_Cache_Core object
$cache = Zend_Cache::factory($cacheFrontendName,
  $cacheBackendName,
  $cacheFrontendOptions,
  $cacheBackendOptions);

// Put it in the registry
$registry->set('cache', $cache);

/** Created long-time cache */
$longTimeCacheFrontendOptions = $cacheFrontendOptions;
$longTimeCacheFrontendOptions['lifetime'] = 86400;
$longTimeCache = Zend_Cache::factory($cacheFrontendName,
  $cacheBackendName,
  $longTimeCacheFrontendOptions,
  $cacheBackendOptions);

/** Put it in the registry */
$registry->set('longTimeCache', $longTimeCache);
?>