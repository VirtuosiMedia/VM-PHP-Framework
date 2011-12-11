<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description A unit test that tests if x is FALSE, fails if it is not.
 * @namespace Test\Assert
 */
namespace Test\Assert;

class False extends \Test\Assert {
	
	/**
	 * @description Tests if x is FALSE, fails if it is not
	 * @param boolean $x - A boolean parameter
	 */
	function __construct($x){
		$this->result = ($x === TRUE) ? FALSE : TRUE;
		if (!$this->result){
			$this->error = "The value '$x' did not evaluate as FALSE as asserted.";
		}		
	}	
}