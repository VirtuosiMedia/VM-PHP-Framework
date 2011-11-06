<?php
/**
* @author Virtuosi Media Inc.
* @license: MIT License
* Description: A unit test that tests if x is NULL, fails if it is not.
*/
class Test_Assert_Null extends Test_Assert {
	
	/**
	 * Tests if x is NULL, fails if it is not
	 * @param mixed $x - The value to test
	 */
	function __construct($x){
		$this->result = (is_null($x)) ? TRUE : FALSE;
		if (!$this->result){
			$this->error = "'$x' was not NULL, contrary to what was asserted.";
		}		
	}	
}
?>