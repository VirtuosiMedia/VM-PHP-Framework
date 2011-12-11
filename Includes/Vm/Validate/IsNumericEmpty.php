<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description A validator for numeric values or strings using the is_numeric function - Returns TRUE if $input 
 *		is a number or a numeric string, FALSE otherwise (including empty strings).
 * @extends Vm\Validator
 * @namespace Vm\Validate
 */
namespace Vm\Validate;

class IsNumericEmpty extends \Vm\Validator {

	/** 
	 * @param string $input - The input to be validated
	 * @param string $error - optional - A custom error message to be returned if the input fails validation
	 */
	function __construct($input, $error = NULL){	
		$defaultError = 'Value must be numeric';
		if (!is_numeric($input)){
			$this->error = ($error) ? $error : $defaultError;
			$this->validates = false;			
		}
	}
}