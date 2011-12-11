<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description A unit test that tests if x is equal to y, fails if they are not equal.
 * @namespace Test\Assert
 */
namespace Test\Assert;

class Equal extends \Test\Assert {
	
	/**
	 * @description Tests if x is equal to y, fails if they are not equal
	 * @param mixed $x - The first parameter
	 * @param mixed $y - The second parameter
	 */
	function __construct($x, $y){
		$this->result = ($x == $y) ? TRUE : FALSE;
		if (!$this->result){
			$this->error = "'$x' did not equal '$y' as asserted.";
		}		
	}	
}