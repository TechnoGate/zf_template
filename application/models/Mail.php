<?php

/**
 * MailService Class
 */
class Mail {

  /** Define the separator used to seperated emails in the log file */
  const SEPARATOR = "===***===\n";

  /**
   * Send an email
   *
   * @param string $subject
   * @param string $destination
   * @param string $textBody
   * @param string $htmlBody
   * @param bool   $forceSend
   */
  public static function send($subject, $destination, $textBody = null, $htmlBody = null, $forceSend = false) {

    if ($forceSend === true || (!runLocal() && !isSimpleTest())) {
      $mail = new Zend_Mail(App::getProperty('app.models.mail.charset', 'utf-8'));
      $mail->setFrom(App::getProperty('app.models.mail.from.email'), App::getProperty('app.models.mail.from.name'));
      $mail->setReplyTo(App::getProperty('app.models.mail.from.email'));

      if(is_array($destination))
        $mail->addTo($destination['email'], $destination['name']);
      else
        $mail->addTo($destination, '');

      // Cc to maintenance
      $bcc = App::getProperty('app.models.mail.bcc.email');
      if ($ccToSelf === true && !empty($bcc))
        $mail->addBcc($bcc);

      // Set the subject
      $mail->setSubject(stripslashes($subject));

      // Set the body
      if(!empty($textBody))
        $mail->setBodyText(stripslashes($textBody));
      if(!empty($htmlBody))
        $mail->setBodyHtml(stripslashes($htmlBody));

      /** Send the mail */
      $tries = 0;
      do {
        try {
          /** Setup the transport */
          $tr = new Zend_Mail_Transport_Smtp(self::_getSmtpHost($tries), self::_getSmtpParams($tries));

          /** Send the mail */
          $mail->send($tr);

          /** Get out of the loop, the mail has been sent */
          break;
        } catch (Zend_Mail_Protocol_Exception $e) {
          $event = array(
            'to ' . $destEmail,
            'subject ' . $subject,
            'error ' . $e->getMessage(),
            'tries ' . $tries,
          );

          /** Create a debugging trace */
          Debug::trace('SENDING_MAIL', $event);

          /** Retry but wait 5*retries seconds before retrying */
          $tries++;
          sleep(5 * $tries);
        }
      } while ($tries < 3);

      if ($tries == 3) {
        throw $e;
      }
    } else {
      $myFile = ROOT_PATH . '/temp/logs/mail.log';
      $fh = fopen($myFile, 'a');
      if(is_array($destination))
        fwrite($fh, "*** To: " . $destination['name'] . '<' . $destination['email'] .  '>' . "\n");
      else
        fwrite($fh, "*** To: " . $destination. "\n");
      fwrite($fh, "*** Subject: " . $subject . "\n\n");
      fwrite($fh, "*** Text Body: " . $textBody . "\n\n");
      fwrite($fh, "*** Html Body: " . $htmlBody . "\n\n");
      fwrite($fh, self::SEPARATOR);
    }

    /** Log the mail */
    Log::logEvent('EMAIL_SENT', $destEmail, $subject);
  }

  /**
   * validates an email address
   *
   * Returns true if and only if $email is a valid email address
   * according to RFC2822
   *
   * @link   http://www.ietf.org/rfc/rfc2822.txt RFC2822
   * @link   http://www.columbia.edu/kermit/ascii.html US-ASCII characters
   * @return bool : true of ok, false if not
   */
  public static function validate_email($email, $allow = Zend_Validate_Hostname::ALLOW_DNS, $validateMx = false, Zend_Validate_Hostname $hostnameValidator = null) {

    /**
     * Create an instance of Zend_Validate_EmailAddress to check the validity
     * of the email.
     */
    $emailValidator = new Zend_Validate_EmailAddress($allow, $validateMx, $hostnameValidator);

    // Make sure the email is lowercase.
    $email = trim(strtolower($email));

    $valid = $emailValidator->isValid($email);

    return $valid;
  }

  /**
   * Enter description here...
   *
   * @param string $templateName : name of the mail template to use
   * @param array $variables : variables to fill in the template. This is an associative array (variable name, variable value)
   * @param string $type : content-type of the mail : text or html. For future use.
   * @return unknown
   */
  public static function getTemplatedMail($templateName, $variables) {

    /** Create a new view Object */
    $view = new Zend_View();

    /** Set the script Path */
    $view->setScriptPath(APPLICATION_PATH . '/views/mailTemplates');

    /** Add view Helpers */
    $view->addHelperPath('Technogate/View/Helper', 'Technogate_View_Helper');

    /** Get the properties of the site */
    $baseUrlServer = Url::getBaseUrl(true);

    /** Send everything to the view */
    foreach ($variables as $k => $v) {
      $view->$k = $v;
    }
    $view->baseUrlServer = $baseUrlServer;

    $view->appTitle = App::getProperty('app.title');

    /** Set the maildirs */
    $mailDirs = array();
    $mailDirs[] = APPLICATION_PATH . "/views/mailTemplates";

    $view->maildirs = implode(PATH_SEPARATOR, $mailDirs);

    /** Set the ecnoding to UTF-8 */
    $view->setEncoding('UTF-8');

    /** Render the view */
    $renderedView = $view->render($templateName);

    /** Return the rendered text */
    return $renderedView;
  }

  /**
   * This function takes a list as an argument (an array is also accepted, however
   * it'll be returned as is), the list can be a string of one or more than email
   * split with spaces, colons and commas
   *
   * @param Array | String $emails : The comma, colon or space seperated list of emails (or an array)
   * @return Array
   */
  public static function splitEmailList($emails) {

    /** initialize the result */
    $result = array();

    /** Check if it's already an array */
    if (!empty($emails) && is_array($emails)) {
      $result = $emails;
    }

    /** split the emails */
    if (!empty($emails) && is_string($emails)) {
      $result = preg_split('/[\s,;]+/', $emails);
    }

    /** don't return the result with empty indexes */
    foreach ($result as $k => $v) {
      if (empty($v)) {
        unset($result[$k]);
      }
    }

    return $result;
  }

  /**
   * This function check each email present in the array $emails and return
   * a list of valid / invalid emails
   *
   * @param Array | String $emails : The comma, colon or space seperated list of emails (or an array)
   * @return Array
   * 		array => (
   * 		    'valid_emails'   => array(),
   * 		    'invalid_emails' => array(),
   * 		)
   */
  public static function checkEmails($emails) {

    /** Initialize the result */
    $result = array(
      'valid_emails' => array(),
      'invalid_emails' => array(),
    );

    /** Check each email */
    foreach ($emails as $email) {
      if (self::validate_email($email) === true) {
        $result['valid_emails'][] = $email;
      } else {
        $result['invalid_emails'][] = $email;
      }
    }

    return $result;
  }

  /**
   * PROTECTED FUNCTIONS
   */

  protected static function _getSmtpHost($tries = 0) {

    // Get the smtp config
    $smtpConfig = self::_getSmtpConfig($tries);

    return $smtpConfig['host'];
  }

  protected static function _getSmtpParams($tries = 0) {

    // Get the smtp config
    $smtpConfig = self::_getSmtpConfig($tries);

    return $smtpConfig['params'];
  }

  protected static function _getSmtpConfig($tries = 0) {

    // Get the SMTP config from the config file
    $smtpConfig = App::getProperty('app.models.mail.smtp');

    // Sanity Check
    if($tries > sizeof($smtpConfig))
      $tries = 0;

    return $smtpConfig[$tries];
  }
}
?>