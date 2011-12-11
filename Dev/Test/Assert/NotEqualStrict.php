<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description A unit test that tests if x is not equal to y with type comparison, fails if they are equal.
 * @namespace Test\Assert
 */
namespace Test\Assert;

class NotEqualStrict extends \Test\Assert {
	
	/**
	 * @description Tests if x is not equal to y with type comparison, fails if they are equal
	 * @param mixed $x - The first parameter
	 * @param mixed $y - The second parameter
	 */
	function __construct($x, $y){
		$this->result = ($x !== $y) ? TRUE : FALSE;
		if (!$this->result){
			$this->error = "'$x' was not inequal to '$y' with a strict type comparison as asserted.";
		}			
	}	
}