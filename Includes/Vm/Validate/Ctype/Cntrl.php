<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description A validator for control characters by using the ctype_cntrl function - Returns TRUE if every character 
 * 		in text	is a control character from the current locale, FALSE otherwise (including empty strings).
 * @extends Vm\Validator
 * @namespace Vm\Validate\Ctype
 */
namespace Vm\Validate\Ctype;

class Cntrl extends \Vm\Validator {

	/** 
	 * @param string $input - The input to be validated
	 * @param string $error - optional - A custom error message to be returned if the input fails validation
	 */
	function __construct($input, $error = NULL){	
		$defaultError = 'This field must contain only control characters';
		if (!ctype_cntrl($input)){
			$this->error = ($error) ? $error : $defaultError;
			$this->validates = false;			
		}
	}
}