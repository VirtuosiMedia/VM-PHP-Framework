<?php
/**
* @author Virtuosi Media Inc.
* @license: MIT License
* Description: A unit test that tests if x is TRUE, fails if it is not.
*/
class Test_Assert_True extends Test_Assert {
	
	/**
	 * Tests if x is TRUE, fails if it is not
	 * @param boolean $x - A boolean parameter
	 */
	function __construct($x){
		$this->result = ($x === TRUE) ? TRUE : FALSE;
		if (!$this->result){
			$this->error = "The value '$x' did not evaluate as TRUE as asserted.";
		}
	}	
}
?>