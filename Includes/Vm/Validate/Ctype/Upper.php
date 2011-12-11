<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description A validator for uppercase alphabetic characters by using the ctype_upper function - Returns TRUE if 
 * 		every character in text is an uppercase letter in the current locale.
 * @extends Vm\Validator
 * @namespace Vm\Validate\Ctype
 */
namespace Vm\Validate\Ctype;

class Upper extends \Vm\Validator {

	/** 
	 * @param string $input - The input to be validated
	 * @param string $error - optional - A custom error message to be returned if the input fails validation
	 */
	function __construct($input, $error = NULL){	
		$defaultError = 'This field must contain only uppercase letters';
		if (!ctype_upper($input)){
			$this->error = ($error) ? $error : $defaultError;
			$this->validates = false;			
		}
	}
}