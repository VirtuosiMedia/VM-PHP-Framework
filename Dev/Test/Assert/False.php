<?php
/**
* @author Virtuosi Media Inc.
* @license: MIT License
* Description: A unit test that tests if x is FALSE, fails if it is not.
*/
class Test_Assert_False extends Test_Assert {
	
	/**
	 * Tests if x is FALSE, fails if it is not
	 * @param boolean $x - A boolean parameter
	 */
	function __construct($x){
		$this->result = ($x === TRUE) ? FALSE : TRUE;
		if (!$this->result){
			$this->error = "The value '$x' did not evaluate as FALSE as asserted.";
		}		
	}	
}
?>