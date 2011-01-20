<?php

/**
 * Error controller, designed to display errors
 *
 */
class ErrorController extends Technogate_Controller_Action {

  public function errorAction() {

    $errors = $this->_getParam( 'error_handler');

    /** Create a trace in the debug table for us to analyse */
    Debug::traceException($errors['exception']);

    switch ($errors->type) {
    case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER :
      case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION :
        // 404 error -- controller or action not found
        $this->getResponse ()->setRawHeader ( 'HTTP/1.1 404 Not Found' );
        $logoUrl = Url::getBaseUrl() . '/' . App::getProperty('app.logo');
        $appTitle = App::getProperty('app.title');
        $baseUrl = Url::getBaseUrl();
        $content = <<<EOH
<html>
    <head>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
        <title>The page you were looking for doesn't exist (404)</title>
        <style type="text/css">
            body { background-color: #fff; font-family: Helvetica, sans-serif; }
            h1 { margin: 10px 0; }
            img { border: 0; }
        </style>
    </head>
    <body>

        <a href="${baseUrl}/"><img src="${logoUrl}" alt="${appTitle}" /></a>
    <h1>The page you were looking for doesn't exist.</h1>
    <p>You may have mistyped the address or the page may have moved.</p>
    </body>
</html>
EOH;
        $this->_helper->layout->disableLayout ();

        break;
      default :
        // application error
        $content = <<<EOH
<h1>Error!</h1>
<p>An unexpected error occurred. Please try again later</p>
EOH;
        break;
    }

    /** Get the trace */
    if (isDevEnvironment() === true || isTestingEnvironment()) {
      /** Get the trace */
      $trace = $errors['exception']->getTrace();

      /** The error message */
      $traceString = '<br /><h2>Exception Class</h2><br />';
      $traceString .= "<pre>";
      $traceString .= get_class($errors['exception']) . "<br/>";
      $traceString .= "</pre><br /><br />";
      $traceString .= '<h2>Error Message</h2><br />';
      $traceString .= "<pre>";
      $traceString .= $errors['exception']->getMessage () . "<br/>";
      $traceString .= "</pre><br /><br /><h2>Error Trace</h2><br />";

      /** Append the trace */

      foreach ($trace as $tr) {
        $traceString .= $tr['file'] . " (" . $tr['line'] . ") <strong>" . $tr['class'] . "->" . $tr['function'] . "</strong>()<br/>";
      }
    } else {
      $traceString = null;
    }

    /** Clear previous content */
    $this->getResponse()->clearBody();

    /** Send the error and the trace */
    $this->view->content = $content;
    $this->view->trace = $traceString;
  }

  public function userBlockedAction() {}

  public function noJsAction() {}
}
?>