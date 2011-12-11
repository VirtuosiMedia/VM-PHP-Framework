<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description A validator for lowercase alphabetic characters by using the ctype_lower function - Returns TRUE if 
 * 		every character in text is a lowercase letter in the current locale, FALSE otherwise (inlcuding empty strings).
 * @extends Vm\Validator
 * @namespace Vm\Validate\Ctype
 */
namespace Vm\Validate\Ctype;

class Lower extends \Vm\Validator {

	/** 
	 * @param string $input - The input to be validated
	 * @param string $error - optional - A custom error message to be returned if the input fails validation
	 */
	function __construct($input, $error = NULL){	
		$defaultError = 'This field must contain only lowercase letters';
		if (!ctype_lower($input)){
			$this->error = ($error) ? $error : $defaultError;
			$this->validates = false;			
		}
	}
}