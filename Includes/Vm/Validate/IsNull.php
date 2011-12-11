<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description A validator for null values using the is_null function - Returns TRUE if var is NULL, FALSE otherwise 
 * 		(including empty strings).
 * @extends Vm\Validator
 * @namespace Vm\Validate
 */
namespace Vm\Validate;

class IsNull extends \Vm\Validator {

	/** 
	 * @param string $input - The input to be validated
	 * @param string $error - optional - A custom error message to be returned if the input fails validation
	 */
	function __construct($input, $error = NULL){	
		$defaultError = 'Value must be NULL';
		if (!is_null($input)){
			$this->error = ($error) ? $error : $defaultError;
			$this->validates = false;			
		}
	}
}