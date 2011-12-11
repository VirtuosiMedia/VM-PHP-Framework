<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description A validator for whitespace characters by using the ctype_space function - Returns TRUE if every 
 * 		character in text creates some sort of white space, FALSE otherwise (including empty strings). Besides the 
 * 		blank character this also includes tab, vertical tab, line feed, carriage return and form feed characters.
 * @extends Vm\Validator
 * @namespace Vm\Validate\Ctype
 */
namespace Vm\Validate\Ctype;

class Space extends \Vm\Validator {

	/** 
	 * @param string $input - The input to be validated
	 * @param string $error - optional - A custom error message to be returned if the input fails validation
	 */
	function __construct($input, $error = NULL){	
		$defaultError = 'This field must contain only whitespace characters';
		if (!ctype_space($input)){
			$this->error = ($error) ? $error : $defaultError;
			$this->validates = false;			
		}
	}
}