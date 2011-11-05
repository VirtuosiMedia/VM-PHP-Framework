<?php
/**
* @author Virtuosi Media Inc.
* @license: MIT License
* Description: A unit test that tests if x is equal to y with type comparison, fails if they are not equal.
*/
class Tests_Test_Assert_EqualStrict extends Tests_Test_Assert {
	
	/**
	 * Tests if x is equal to y with type comparison, fails if they are not equal
	 * @param mixed $x - The first parameter
	 * @param mixed $y - The second parameter
	 */
	function __construct($x, $y){
		$this->result = ($x === $y) ? TRUE : FALSE;
		if (!$this->result){
			$this->error = "'$x' did not equal '$y' with a strict type comparison as asserted.";
		}	
	}	
}
?>