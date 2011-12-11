<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description A validator for alphabetic characters by using the ctype_alpha function - Returns TRUE if every 
 * 		character in text is a letter from the current locale, FALSE otherwise (including empty strings).
 * @extends Vm\Validator
 * @namespace Vm\Validate\Ctype
 */
namespace Vm\Validate\Ctype;

class Alpha extends \Vm\Validator {

	/** 
	 * @param string $input - The input to be validated
	 * @param string $error - optional - A custom error message to be returned if the input fails validation
	 */
	function __construct($input, $error = NULL){	
		$defaultError = 'This field must contain only contain letters';
		if (!ctype_alpha($input)){
			$this->error = ($error) ? $error : $defaultError;
			$this->validates = false;			
		}
	}
}