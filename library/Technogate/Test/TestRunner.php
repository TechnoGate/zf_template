<?php

/**
 * Scan the whole $testBaseDir looking for php files,
 * Create an array out of the found files, the key of
 * the array is the groupTest and the value is an array
 * of class names, following the rule:
 * testBaseDirName_groupTest1_Test1
 * Where testBaseDirName is the name of testBaseDir and
 * not the path
 *
 * Example
 * testBaseDir
 *      ----> groupTest1
 *          -----> Test1.php
 *          -----> Test2.php
 *      ----> groupTest2
 *          -----> Test1.php
 *          -----> Test2.php
 *
 * The function will return:
 * Array
 * (
 *    [0] => groupTest1
 *    [1] => groupTest2
 *    [groupTest1] => Array
 *        (
 *            [0] => testBaseDirName_groupTest1_Test1
 *            [1] => testBaseDirName_groupTest1_Test2
 *        )
 *
 *    [groupTest2] => Array
 *        (
 *            [0] => testBaseDirName_groupTest1_Test1
 *            [1] => testBaseDirName_groupTest1_Test2
 *        )
 *
 * )
 *
 *
 * @param string $testBaseDir The root folder where the tests are located
 * @return array See The description above
 */
function scanFolderForTestClasses($testBaseDir) {

  $testSuites = array();

  // Scan the folder..
  if(is_dir($testBaseDir) && ($openTestBaseDir = opendir($testBaseDir)) !== false) {
    while (($groupTest = readdir($openTestBaseDir)) !== false) {
      if($groupTest == '.' || $groupTest == '..'  || $groupTest == '.svn') {
        continue;
      }
      $groupTestDir = $testBaseDir . '/' . $groupTest;
      if( is_dir($groupTestDir) && ($openGroupTest = opendir($groupTestDir)) !== false ) {
        // Ok we got the first group, create an array for it
        $testSuites[$groupTest] = array();
        while(($testFile = readdir($openGroupTest)) !== false ) {
          $testFileDir = $groupTestDir . '/' . $testFile;
          if(is_file($testFileDir)) {
            // Skip any non .php files, extension is PHP
            if(strpos($testFile, '.php') === false || strpos($testFile, '.php') != strlen($testFile) - 4) {
              continue;
            }

            $testClassName = basename($testBaseDir) . '_' . $groupTest . '_' . str_replace('.php', '', $testFile);
            $testSuites[$groupTest][] = $testClassName;
          }
        }
      }
    }
  }

  return $testSuites;
}

/**
 * This function will display or send $testOutput
 * depending on from where we ran the test.
 *
 * @param string $testOutput
 */
function sendDisplayOutput($testOutput, $testResult) {

  // Send the mail or display the output!
  if (TextReporter::inCli()) {

    // Mail the report using Technogate_Mail
    $testResult = ($testResult === true)? "PASS" : "FAIL";
    $emailSubject = '[Technogate Test][' . $testResult .'] ' . getCurrentDate();
    $emailSender = "Test Report";
    $ccToDaniel = true;
    $forceSend = true;
    $type = 'html';

    // Don't add Maintenance's email here, it's Bcc by default!
    $destEmail = "wael@technogate.fr";

    // Send the email!
    Technogate_Mail::send($emailSubject, $emailSender, $testOutput, $destEmail, $type, $ccToDaniel, $forceSend);
  } else {
    // Display it
    echo $testOutput;
  }
}

?>