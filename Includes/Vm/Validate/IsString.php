<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description A validator for strings using the is_string function - Returns TRUE if $input is of type string, 
 * 		FALSE otherwise.
 * @extends Vm\Validator
 * @namespace Vm\Validate
 */
namespace Vm\Validate;

class IsString extends \Vm\Validator {

	/** 
	 * @param string $input - The input to be validated
	 * @param string $error - optional - A custom error message to be returned if the input fails validation
	 */
	function __construct($input, $error = NULL){	
		$defaultError = 'Value must be a string';
		if (!is_string($input)){
			$this->error = ($error) ? $error : $defaultError;
			$this->validates = false;			
		}
	}
}