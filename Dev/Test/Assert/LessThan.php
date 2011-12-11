<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description A unit test that tests if x is less than y, fails if x is greater than or equal to y.
 * @namespace Test\Assert
 */
namespace Test\Assert;

class LessThan extends \Test\Assert {
	
	/**
	 * @description Tests if x is less than y, fails if x is greater than or equal to y
	 * @param num $x - The first number
	 * @param num $y - The second number
	 */
	function __construct($x, $y){
		$this->result = ($x < $y) ? TRUE : FALSE;
		if (!$this->result){
			$this->error = "'$x' was not less than '$y' as asserted.";
		}		
	}	
}