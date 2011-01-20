<?php

/**
 * Technogate
 *
 * Please remove the entire Dummy folder once a real test has been added
 *
 * @package Technogate_Dummy_Passes
 * @author Wael Nasreddine (TechnoGate) <wael@technogate.fr>
 */
class Technogate_Dummy_Passes extends UnitTestCase {

	public function __construct(){

		parent::__construct('Dummy passing test');
	}

	public function testPassing() {

		$this->assertTrue(true, 'Dummy test');
	}
}
?>