<?php
/**
* @author Virtuosi Media Inc.
* @license: MIT License
* @description: Signals an incomplete unit test.
*/
class Tests_Test_Incomplete extends Tests_Test_Assert {
	
	function __construct(){
		$this->result = 'Incomplete';
		$this->error = 'This test is incomplete and is meant as a placeholder until an actual test can be written.';		
	}	
}
?>