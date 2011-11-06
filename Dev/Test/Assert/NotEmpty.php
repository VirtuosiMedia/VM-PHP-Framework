<?php
/**
* @author Virtuosi Media Inc.
* @license: MIT License
* Description: A unit test that tests if x is not empty, fails if it is.
*/
class Test_Assert_NotEmpty extends Test_Assert {
	
	/**
	 * Tests if x is not empty, fails if it is
	 * @param mixed $x - The value to test
	 */
	function __construct($x){
		$this->result = (empty($x)) ? FALSE : TRUE;
		if (!$this->result){
			$this->error = "'$x' did not contain a value as asserted.";
		}		
	}	
}
?>