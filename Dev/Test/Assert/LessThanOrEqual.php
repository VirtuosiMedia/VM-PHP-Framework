<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description A unit test that tests if x is less than or equal to y, fails if x is greater than y.
 * @namespace Test\Assert
 */
namespace Test\Assert;

class LessThanOrEqual extends \Test\Assert {
	
	/**
	 * @description Tests if x is less than or equal to y, fails if x is greater than y
	 * @param num $x - The first number
	 * @param num $y - The second number
	 */
	function __construct($x, $y){
		$this->result = ($x <= $y) ? TRUE : FALSE;
		if (!$this->result){
			$this->error = "'$x' was not less than or equal to '$y' as asserted.";
		}		
	}	
}