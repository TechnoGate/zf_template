<?php

/**
 * Technogate
 *
 * @package Index
 * @author Wael Nasreddine (TechnoGate) <wael@technogate.fr>
 */

// PHP_VERSION_ID is available as of PHP 5.2.7, if our
// version is lower than that, then emulate it
if (!defined('PHP_VERSION_ID')) {
  $version = explode('.', PHP_VERSION);

  define('PHP_VERSION_ID', ($version[0] * 10000 + $version[1] * 100 + $version[2]));
}

// PHP_VERSION_ID is defined as a number, where the higher the number
// is, the newer a PHP version is used. It's defined as used in the above
// expression:
//
// $version_id = $major_version * 10000 + $minor_version * 100 + $release_version;
//
// Now with PHP_VERSION_ID we can check for features this PHP version
// may have, this doesn't require to use version_compare() everytime
// you check if the current PHP version may not support a feature.
//
// For example, we may here define the PHP_VERSION_* constants thats
// not available in versions prior to 5.2.7

if (PHP_VERSION_ID < 50207) {
  define('PHP_MAJOR_VERSION',   $version[0]);
  define('PHP_MINOR_VERSION',   $version[1]);
  define('PHP_RELEASE_VERSION', $version[2]);
}

// Define path to root directory
if (! defined('ROOT_PATH'))
  define('ROOT_PATH', realpath(dirname(__FILE__) . '/..'));

// Define path to application directory
if (! defined('APPLICATION_PATH'))
  define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

// Define path to public directory
if (! defined('PUBLIC_PATH'))
  define('PUBLIC_PATH', realpath(ROOT_PATH . '/public'));

// Define application environment
if (! defined('APPLICATION_ENV'))
  define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

if(! defined('WRITABLE_FOLDERS'))
  define('WRITABLE_FOLDERS', implode(':', array(
    ROOT_PATH . '/.zfcache',
    PUBLIC_PATH . '/img/g/',
    PUBLIC_PATH . '/uploads',
  )));

if(! defined('WRITABLE_FILES'))
  define('WRITABLE_FILES', implode(':', array(
    ROOT_PATH . '/temp/logs/mail.log',
  )));

// Set include path
set_include_path(APPLICATION_PATH
  . PATH_SEPARATOR . realpath(ROOT_PATH . '/library')
  . PATH_SEPARATOR . realpath(ROOT_PATH . '/library/Zend/library')
  . PATH_SEPARATOR . realpath(ROOT_PATH . '/library/php-activerecord')
  . PATH_SEPARATOR . realpath(APPLICATION_PATH . '/models/')
  . PATH_SEPARATOR . realpath(APPLICATION_PATH . '/models/Exceptions')
  . PATH_SEPARATOR . realpath(APPLICATION_PATH . '/forms/')
  . PATH_SEPARATOR . get_include_path());

// Check requirement.
if(PHP_MAJOR_VERSION < 5 || (PHP_MAJOR_VERSION >= 5 && PHP_MINOR_VERSION < 3))
  die("You are running PHP Version " . PHP_VERSION . ", however this application require PHP 5.3 and above");
if (! file_exists(PUBLIC_PATH . '/.htaccess'))
  die("You should copy public/.htaccess.sample to public/.htaccess");
if (! file_exists(APPLICATION_PATH . '/configs/application.ini'))
  die("You should copy application/configs/application.ini.sample to application/configs/application.ini");
foreach(explode(':', WRITABLE_FOLDERS) as $folder) {
  if (! (is_dir($folder) && is_writable($folder)))
    die("The folder ${folder} is not writable or does not exist.");
}
foreach(explode(':', WRITABLE_FILES) as $file) {
  if (! (is_file($file) && is_writable($file)))
    die("The file ${file} is not writable or does not exist.");
}

/** Common Function */
require_once 'Technogate/Common.php';

/** Zend_Application */
require_once 'Zend/Application.php';

/** Zend_Loader_Autoload */
require_once 'Zend/Loader/Autoloader.php';

/** Set the loader as Fallback loader */
/** TODO: Resolve this so it won't be needed to set it as fallback */
$loader = Zend_Loader_Autoloader::getInstance();
$loader->registerNamespace('Technogate_');
$loader->setFallbackAutoloader(true);

// Create application, bootstrap, and run
$appHome = APPLICATION_PATH . '/configs/application.ini';
$appEnv = APPLICATION_ENV;
$application = new Zend_Application($appEnv, $appHome);
$application->bootstrap()->run();

/**
 * We don't close PHP tag to avoid sending headers
 */