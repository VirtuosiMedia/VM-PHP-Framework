<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description A validator for booleans using the is_bool function - Returns TRUE if $input is a boolean, FALSE 
 * 		otherwise
 * @extends Vm\Validator
 * @namespace Vm\Validate
 */
namespace Vm\Validate;

class IsBool extends \Vm\Validator {

	/**
	 * @param string $input - The input to be validated
	 * @param string $error - optional - A custom error message to be returned if the input fails validation
	 */
	function __construct($input, $error = NULL){	
		$defaultError = 'Value must be boolean';
		if (!is_bool($input)){
			$this->error = ($error) ? $error : $defaultError;
			$this->validates = false;			
		}
	}
}