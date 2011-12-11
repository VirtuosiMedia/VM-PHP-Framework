<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description A validator for punctuation characters by using the ctype_punct function - Returns TRUE if every 
 * 		character in text is printable, but neither letter, digit or blank, FALSE otherwise (including empty strings).
 * @extends Vm\Validator
 * @namespace Vm\Validate\Ctype
 */
namespace Vm\Validate\Ctype;

class Punct extends \Vm\Validator {

	/** 
	 * @param string $input - The input to be validated
	 * @param string $error - optional - A custom error message to be returned if the input fails validation
	 */
	function __construct($input, $error = NULL){	
		$defaultError = 'This field must contain only punctuation characters';
		if (!ctype_punct($input)){
			$this->error = ($error) ? $error : $defaultError;
			$this->validates = false;			
		}
	}
}