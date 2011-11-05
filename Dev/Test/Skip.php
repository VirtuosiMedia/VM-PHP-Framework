<?php
/**
* @author Virtuosi Media Inc.
* @license: MIT License
* @description: Skips a unit test.
*/
class Tests_Test_Skip extends Tests_Test_Assert {
	
	/**
	 * @param string $reason - The reason why the test was skipped
	 */
	function __construct($reason){
		$this->result = 'Skipped';
		$this->error = $reason;		
	}	
}
?>