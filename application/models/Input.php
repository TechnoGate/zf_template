<?php

/**
 * Technogate
 *
 * @package InputService
 * @author Wael Nasreddine (TechnoGate) <wael@technogate.fr>
 */
class Input {

  /**
   * Filter the input given using the options
   *
   * @param string $input
   * @param array $options
   * @return string
   */
  public static function filter($input, $options = null) {

    // HTML Tags filter
    if ($options == null || empty($options['allowedHtmlTags'])) {
      $newInput = strip_tags($input);
    } else {
      // Filter the tags leaving only the allowed ones.
      $newInput = strip_tags($input, $options['allowedHtmlTags']);

      // Is iFrame one of allowed tags ?
      // Should we filter it by domain too ?
      if (strpos($options['allowedHtmlTags'], '<iframe>') && !empty($options['allowedIframeDomains'])) {
        // TODO: Filter iframe by domain!!
      }
    }

    return $newInput;
  }

}
?>