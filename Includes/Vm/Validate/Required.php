<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description A validator for required fields - Returns TRUE if minimum length of 1 is met, FALSE otherwise. 
 * @note Vm\Validate\Required will automatically trim the input of whitespace
 * @extends Vm\Validator
 * @namespace Vm\Validate
 */
namespace Vm\Validate;

class Required extends \Vm\Validator {

	/**
	 * @param int $minLength - The minimum number of characters	
	 * @param mixed $input - The input to be validated
	 * @param string $error - optional - A custom error message to be returned if the input fails validation
	 */
	function __construct($input, $error = NULL){	
		$defaultError = "This field is required";
		if (is_array($input)){
			$errorExists = (sizeof($input) < 1) ? TRUE : FALSE;	
		} else {
			$input = trim($input);	
			$errorExists = (strlen($input) < 1) ? TRUE : FALSE;
		}
		
		if ($errorExists){
			$this->error = ($error) ? $error : $defaultError;
			$this->validates = false;			
		}		
	}
}