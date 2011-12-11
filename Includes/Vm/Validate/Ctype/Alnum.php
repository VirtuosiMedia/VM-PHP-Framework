<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description A validator for alphanumeric characters by using the ctype_alnum function - Returns TRUE if every 
 * 		character in text is either a letter or a digit, FALSE otherwise (including empty strings).
 * @extends Vm\Validator
 * @namespace Vm\Validate\Ctype
 */
namespace Vm\Validate\Ctype;

class Alnum extends \Vm\Validator {

	/**
	 * @param string $input - The input to be validated
	 * @param string $error - optional - A custom error message to be returned if the input fails validation
	 */
	function __construct($input, $error = NULL){	
		$defaultError = 'This field must contain only letters or numbers';
		if (!ctype_alnum($input)){
			$this->error = ($error) ? $error : $defaultError;
			$this->validates = false;			
		}
	}
}