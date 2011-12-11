<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description A unit test that tests if x is not empty, fails if it is.
 * @namespace Test\Assert
 */
namespace Test\Assert;

class NotEmpty extends \Test\Assert {
	
	/**
	 * @description Tests if x is not empty, fails if it is
	 * @param mixed $x - The value to test
	 */
	function __construct($x){
		$this->result = (empty($x)) ? FALSE : TRUE;
		if (!$this->result){
			$this->error = "'$x' did not contain a value as asserted.";
		}		
	}	
}