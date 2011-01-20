<?php

/**
 * Bootstrap
 *
 */
require_once realpath(dirname(__FILE__)) . '/bootstrap.php';

/* Find out which operation it is */
switch ($argv[1]) {
case '--operation=deploy-prod':
  $Operation = 'deploy-prod';
  break;
case '--operation=deploy-preprod':
  default:
    $Operation = 'deploy-preprod';
    break;
}

/** Before running any test, make sure no user is currently logged in */
Zend_Auth::getInstance()->clearIdentity();

/* Where the tests are located ? */
$testBaseDir = ROOT_PATH . '/tests/Technogate';

/* Scan for test suites */
$testSuites = scanFolderForTestClasses($testBaseDir);

/** For each test suite, add tests */
$testObjects = array();
if(count ($testSuites) > 0 ){
  foreach ($testSuites as $testSuite => $classes) {
    if (count ($classes) > 0) {
      $$testSuite = new TestSuite('Technogate' . $testSuite . ' Tests');
      foreach ($classes as $class) {
        $$testSuite->add(new $class);
      }
      $testObjects[] = $$testSuite;
    }
  }
}

/* Run the tests now, if any of course */
if(count($testObjects) > 0) {
  $testOutput = "";
  $seperator = "";

  // Iterate through each instance of the test classes and run the test inside a recorded buffer.
  $testResult = true;
  foreach ($testObjects as $testObject) {
    // Start the buffer
    ob_start();

    if($Operation == 'deploy-prod') {
      $testResult = $testObject->run(new TextReporter());
    } else { /* $Operation == 'deploy-preprod' */
      $testResult = $testObject->run(new HtmlReporter('utf-8'));
    }

    // Capture the output and add it to our content!
    $testOutput .= $seperator . ob_get_contents();

    // Clean the buffer
    ob_end_clean();

    // The seperator should always be two empty lines except for the
    // first time
    $seperator = "\n\n";
  }

}

// Send or Display the test result.
if($Operation == 'deploy-prod') {
  echo $testOutput;
  if ($testResult === true) {
    exit(0);
  } else {
    exit(1);
  }
} else { /* $Operation == 'deploy-preprod' */
  sendDisplayOutput($testOutput, $testResult);
}

?>