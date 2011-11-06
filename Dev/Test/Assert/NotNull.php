<?php
/**
* @author Virtuosi Media Inc.
* @license: MIT License
* Description: A unit test that tests if x is not NULL, fails if it is.
*/
class Test_Assert_NotNull extends Test_Assert {
	
	/**
	 * Tests if x is not NULL, fails if it is
	 * @param mixed $x - The value to test
	 */
	function __construct($x){
		$this->result = (is_null($x)) ? FALSE : TRUE;
		if (!$this->result){
			$this->error = "'$x' was NULL, contrary to what was asserted.";
		}			
	}	
}
?>