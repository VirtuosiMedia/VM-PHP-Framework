<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description A unit test that tests if x is not NULL, fails if it is.
 * @namespace Test\Assert
 */
namespace Test\Assert;

class NotNull extends \Test\Assert {
	
	/**
	 * @description Tests if x is not NULL, fails if it is
	 * @param mixed $x - The value to test
	 */
	function __construct($x){
		$this->result = (is_null($x)) ? FALSE : TRUE;
		if (!$this->result){
			$this->error = "'$x' was NULL, contrary to what was asserted.";
		}			
	}	
}