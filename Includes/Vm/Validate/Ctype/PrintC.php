<?php
/**
 * @author Virtuosi Media
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description A validator for printable characters by using the ctype_print function - Returns TRUE if every 
 * 		character in text  will actually create output (including blanks). Returns FALSE if text contains control 
 * 		characters or characters that do not have any output or control function at all (including empty strings).
 * @extends Vm\Validator
 * @namespace Vm\Validate\Ctype
 */
namespace Vm\Validate\Ctype;

class PrintC extends \Vm\Validator {

	/** 
	 * @param string $input - The input to be validated
	 * @param string $error - optional - A custom error message to be returned if the input fails validation
	 */
	function __construct($input, $error = NULL){	
		$defaultError = 'This field must contain only printable characters';
		if (!ctype_print($input)){
			$this->error = ($error) ? $error : $defaultError;
			$this->validates = false;			
		}
	}
}