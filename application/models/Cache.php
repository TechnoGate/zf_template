<?php

/**
 * Technogate
 *
 * @package CacheService
 * @author Wael Nasreddine (TechnoGate) <wael@technogate.fr>
 */
class Cache {

  /** Cache names */
  const CACHE_NORMAL = 'cache';
  const CACHE_LONGTIME = 'longTimeCache';

  /**
   * This function return true if the cache is valid (not false and instance of Zend_Cache)
   *
   * @param Zend_Cache | bool $cache
   * @return bool
   */
  protected static function _isValid($cache) {

    if($cache !== false && $cache instanceof Zend_Cache_Core) {
      $result = true;
    } else {
      $result = false;
    }

    return $result;
  }

  /**
   * This function returns the cache entry (if possible)
   *
   * @return Zend_Cache | bool
   */
  public static function getCache($cacheName = self::CACHE_NORMAL) {

    /** Load the registry */
    $registry = Zend_Registry::getInstance();

    /** Clear the cache around the comments */
    if($registry->isRegistered($cacheName)) {
      $cache = $registry->get($cacheName);
    } else {
      $cache = false;
    }

    return $cache;
  }

  /**
   * This function loads an entry from cache
   *
   * @param String $cacheEntryName
   * @param String $cacheName
   * @return Mixed
   */
  public static function load($cacheEntryName, $cacheName = self::CACHE_NORMAL) {

    /** Get the cache */
    $cache = self::getCache($cacheName);

    /** Initialize the result */
    $result = false;

    /** load the cache */
    if(self::_isValid($cache) === true) {
      $result = $cache->load($cacheEntryName);
    }

    return $result;
  }

  /**
   * This function saves a result into an id
   *
   * @param Mixed  $cacheValue      : The value
   * @param String $cacheEntryName  : The entry name (The id of the cache entry)
   * @param Array  $cacheTags       : The tags under which to save the cache
   * @param String $cacheName       : The name of the cache (the one in Zend_Registry)
   * @return bool
   */
  public static function save($cacheValue, $cacheEntryName, $cacheTags = array(), $cacheName = self::CACHE_NORMAL) {

    /** get the cache */
    $cache = self::getCache($cacheName);

    /** Initialize the result */
    $result = false;

    if(self::_isValid($cache) === true) {
      $result = $cache->save($cacheValue, $cacheEntryName, $cacheTags);
    }

    return $result;
  }

  /**
   * This function remove an entry from cache
   *
   * @param String $cacheEntryName  : The entry name (The id of the cache entry)
   * @param String $cacheName       : The name of the cache (the one in Zend_Registry)
   * @return bool
   */
  public static function remove($cacheEntryName, $cacheName = self::CACHE_NORMAL) {

    /** get the cache */
    $cache = self::getCache($cacheName);

    /** Initialize the result */
    $result = false;

    if(self::_isValid($cache) === true) {
      $result = $cache->remove($cacheEntryName);
    }

    return $result;
  }

  /**
   * This function clean cache matching tags
   *
   * @param Array   $cacheTags     : The tags under which to save the cache
   * @param String  $cacheName     : The name of the cache (the one in Zend_Registry)
   * @param String  $cleaningMode  : Constants from the class Zend_Cache.
   * @return unknown_type
   */
  public static function cleanTags($cacheTags, $cacheName = self::CACHE_NORMAL, $cleaningMode = Zend_Cache::CLEANING_MODE_MATCHING_ANY_TAG) {

    /** get the cache */
    $cache = self::getCache($cacheName);

    /** Initialize the result */
    $result = false;

    if(self::_isValid($cache) === true) {
      $result = $cache->clean($cleaningMode, $cacheTags);
    }

    return $result;
  }

  /**
   * This function clean cache (either the old or everything)
   *
   * @param String  $cacheName     : The name of the cache (the one in Zend_Registry)
   * @param String  $cleaningMode  : Constants from the class Zend_Cache.
   * @return unknown_type
   */
  public static function clean($cacheName = self::CACHE_NORMAL, $cleaningMode = Zend_Cache::CLEANING_MODE_OLD) {

    /** get the cache */
    $cache = self::getCache($cacheName);

    /** Initialize the result */
    $result = false;

    if(self::_isValid($cache) === true) {
      $result = $cache->clean($cleaningMode);
    }

    return $result;
  }
}
?>