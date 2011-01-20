<?php

/**
 * Technogate
 *
 * @package Bootstrap
 * @author Wael Nasreddine (TechnoGate) <wael@technogate.fr>
 */
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap {

  /**
   * Initialize auto loading of classes.
   *
   * @return object Zend_Application_Module_Autoloader
   */
  protected function _initAutoload() {

    $moduleLoader = new Zend_Application_Module_Autoloader(array(
      'namespace' => 'Technogate_',
      'basePath' => APPLICATION_PATH
    ));

    return $moduleLoader;
  }

  /**
   * Initialize the Technogate library
   *
   */
  protected function _initTechnogateLib() {}

  /**
   * Initialize FirePHP logger!
   *
   */
  protected function _initFirePHP() {

    // Instantiate the log
    $log = new Zend_Log();

    // Get the dbAdapter
    //$dbAdapter = $this->getResource('db');

    // Are we running on a dev envirenment?
    if (isDevEnvironment() === true || isTestingEnvironment() === true) {
      // Instantiate the writer to firebug
      $writer = new Zend_Log_Writer_Firebug();

      /** Define a profiler which dbAdapter will use to send all queries to Firebug */
      $profiler = new Zend_Db_Profiler_Firebug('All DB Queries');
      $profiler->setEnabled(true);
      //$dbAdapter->setProfiler($profiler);
    } else {
      // We are on production so no FirePHP logger.
      $writer = new Zend_Log_Writer_Null();
    }

    // Set the writer in the log
    $log->addWriter($writer);

    // Put it in the Registery
    Zend_Registry::set('firePhpLogger', $log);
  }

  /**
   * Initialize Action Helper.
   *
   */
  protected function _initActionHelpers() {

    // Add Helpers path
    Zend_Controller_Action_HelperBroker::addPath(APPLICATION_PATH . '/modules/default/controllers/helpers', 'Helper');
    Zend_Controller_Action_HelperBroker::addPath(APPLICATION_PATH . '/modules/iphone/controllers/helpers', 'Helper');

    // Register helpers
    Zend_Controller_Action_HelperBroker::getStaticHelper('Analytics');
    Zend_Controller_Action_HelperBroker::getStaticHelper('Authenticate');
    Zend_Controller_Action_HelperBroker::getStaticHelper('Browser');
    Zend_Controller_Action_HelperBroker::getStaticHelper('Database');
    Zend_Controller_Action_HelperBroker::getStaticHelper('View');
    Zend_Controller_Action_HelperBroker::getStaticHelper('Config');
  }

  /**
   * Init the view helpers
   */
  protected function _initViewHelpers() {

    /** Get the view Rendere */
    $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('ViewRenderer');

    /** Init the view */
    $viewRenderer->initView();

    /** Set the path to our view helpers */
    $viewRenderer->view->addHelperPath('Technogate/View/Helper', 'Technogate_View_Helper');
  }

  /**
   * Initialize session!
   *
   * @return Zend_Session_Namespace
   */
  protected function _initSession() {

    $sessionOptions = $this->getOption('session');
    $session = new Zend_Session_Namespace($sessionOptions ['name']);
    return $session;
  }

  protected function _initCache() {

    // registry
    $registry = Zend_Registry::getInstance();

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
    $cache = Zend_Cache::factory(
      $cacheFrontendName,
      $cacheBackendName,
      $cacheFrontendOptions,
      $cacheBackendOptions
    );

    // Put it in the registry
    $registry->set(Cache::CACHE_NORMAL, $cache);

    /** Created long-time cache */
    $longTimeCacheFrontendOptions = $cacheFrontendOptions;
    $longTimeCacheFrontendOptions['lifetime'] = 86400;
    $longTimeCache = Zend_Cache::factory(
      $cacheFrontendName,
      $cacheBackendName,
      $longTimeCacheFrontendOptions,
      $cacheBackendOptions
    );

    /** Put it in the registry */
    $registry->set(Cache::CACHE_LONGTIME, $longTimeCache);
  }

  protected function _initLocale() {

    /* Get the registry instance */
    $registry = Zend_Registry::getInstance();

    /** Get the cache */
    $cache = $registry->get(Cache::CACHE_LONGTIME);

    /** cache the locale */
    Zend_Translate::setCache($cache);

    /** Get the locale from the browser */
    $locale = new Zend_Locale(Zend_Locale::BROWSER);

    /** Parse the default language */
    $locales = Zend_Locale::getDefault();
    $default_locale = getFirstKey($locales);

    /** Validate the requested locale */
    if(Zend_Locale::isLocale($locale, true) === false) {
      $locale = $default_locale;
    }

    /** Set the default locale */
    if($locale != $default_locale)
      Zend_Locale::setDefault($locale, '1');
    $registry->set('Zend_Locale', $locale);

    /** Register the used locale in the registry */
    $registry->set('locale', $locale);
  }

  protected function _initFormI18n() {

    /** Get the locale */
    $locale = Zend_Registry::get('locale');

    $language = strtolower(preg_replace('/(.*)_.*/i', '${1}', $locale));

    $translator = new Zend_Translate(
      'array',
      ROOT_PATH . '/data/zend-locales',
      $language,
      array('scan' => Zend_Translate::LOCALE_DIRECTORY)
    );

    $log = Zend_Registry::get('firePhpLogger');

    $translator->setOptions(array(
      'locale' => $locale,
      'log' 	 => $log,
      'logUntranslated' => false
    ));

    Zend_Validate_Abstract::setDefaultTranslator($translator);
  }

  protected function _initRouter() {

    // setup controller
    $frontController = Zend_Controller_Front::getInstance();

    // Set up router for clean URLs
    $router = $frontController->getRouter(); // returns a rewrite router by default

    /**
     * This router is for tokens
     */
    $router->addRoute(
      't',
      new Zend_Controller_Router_Route('t/:token_hash', array('controller' => 'token', 'action' => 'act-by-hash'))
    );

    /**
     * Add login / logout / register shortcuts
     */
    $router->addRoute(
      'login',
      new Zend_Controller_Router_Route('login', array('controller' => 'users', 'action' => 'login'))
    );
    $router->addRoute(
      'logout',
      new Zend_Controller_Router_Route('logout', array('controller' => 'users', 'action' => 'logout'))
    );
    $router->addRoute(
      'register',
      new Zend_Controller_Router_Route('register', array('controller' => 'users', 'action' => 'register'))
    );
  }

} // class

/**
 * We don't close PHP tag to avoid sending headers
 */