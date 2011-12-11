<?php
/**
 * @author Virtuosi Media Inc.
 * @license: MIT License
 * @description: Skips a unit test.
 * @namespace Test
 */
namespace Test;

class Skip extends Assert {
	
	/**
	 * @param string $reason - The reason why the test was skipped
	 */
	function __construct($reason){
		$this->result = 'Skipped';
		$this->error = $reason;		
	}	
}