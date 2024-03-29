<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description A validator for integers using the is_int function - Returns TRUE if var is an integer, 
 *	FALSE otherwise (including empty or numeric strings).
 * @extends Vm\Validator
 * @namespace Vm\Validate
 */
namespace Vm\Validate;

class IsInt extends \Vm\Validator {

	/** 
	 * @param string $input - The input to be validated
	 * @param string $error - optional - A custom error message to be returned if the input fails validation
	 */
	function __construct($input, $error = NULL){	
		$defaultError = 'Value must be an integer';
		if (!is_int($input)){
			$this->error = ($error) ? $error : $defaultError;
			$this->validates = false;			
		}
	}
}