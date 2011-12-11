<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description A validator for numeric values or strings using the is_numeric function - Returns TRUE if $input 
 *	is a number, a numeric string, or empty, FALSE otherwise 
 * @extends Vm\Validator
 * @namespace Vm\Validate
 */
namespace Vm\Validate;

class IsNumeric extends \Vm\Validator {

	/** 
	 * @param string $input - The input to be validated
	 * @param string $error - optional - A custom error message to be returned if the input fails validation
	 */
	function __construct($input, $error = NULL){	
		$defaultError = 'Value must be numeric';
		if ((!is_numeric($input)) && (!empty($input))){
			$this->error = ($error) ? $error : $defaultError;
			$this->validates = false;			
		}
		
	}
}