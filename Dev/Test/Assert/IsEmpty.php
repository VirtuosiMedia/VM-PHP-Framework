<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description A unit test that tests if x is empty, fails if it is not.
 * @namespace Test\Assert
 */
namespace Test\Assert;

class IsEmpty extends \Test\Assert {
	
	/**
	 * @description Tests if x is empty, fails if it is not
	 * @param mixed $x - The value to test
	 */
	function __construct($x){
		$this->result = (empty($x)) ? TRUE : FALSE;
		if (!$this->result){
			$this->error = "'$x' was not empty as asserted.";
		}		
	}	
}