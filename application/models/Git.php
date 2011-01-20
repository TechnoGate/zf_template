<?php

/**
 * Technogate
 *
 * @package GitService
 * @author Wael Nasreddine (TechnoGate) <wael@technogate.fr>
 */
class Git {

  public static function getBranchVersion($branch = null) {

    /** Find out which branch if no branch given */
    if($branch === null) {
      $branch = self::getCurrentBranch();
    }

    /** Make sure we're on the root of the Technogate */
    chdir(ROOT_PATH);

    /** Get the branch version */
    $version = trim(shell_exec("git describe --always " . $branch));

    return $version;
  }

  public static function getCurrentBranch() {

    /** Make sure we're on the root of the Technogate */
    chdir(ROOT_PATH);

    /** get the branches */
    $branches = shell_exec("git branch --no-color 2> /dev/null");

    /** Get the current branch */
    preg_match("#^\* (.*)#mi", $branches, $matches);
    if(count($matches) >= 2) {
      $branch = $matches[1];
    }

    return $branch;
  }
}