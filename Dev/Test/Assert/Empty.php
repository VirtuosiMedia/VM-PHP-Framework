<?php
/**
* @author Virtuosi Media Inc.
* @license: MIT License
* Description: A unit test that tests if x is empty, fails if it is not.
*/
class Test_Assert_Empty extends Test_Assert {
	
	/**
	 * Tests if x is empty, fails if it is not
	 * @param mixed $x - The value to test
	 */
	function __construct($x){
		$this->result = (empty($x)) ? TRUE : FALSE;
		if (!$this->result){
			$this->error = "'$x' was not empty as asserted.";
		}		
	}	
}
?>