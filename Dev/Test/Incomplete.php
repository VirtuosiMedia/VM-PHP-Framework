<?php
/**
* @author Virtuosi Media Inc.
* @license: MIT License
* @description: Signals an incomplete unit test.
*/
class Test_Incomplete extends Test_Assert {
	
	function __construct(){
		$this->result = 'Incomplete';
		$this->error = 'This test is incomplete and is meant as a placeholder until an actual test can be written.';		
	}	
}
?>