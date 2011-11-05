<?php
/**
* @author Virtuosi Media Inc.
* @license: MIT License
* Description: A validator for punctuation characters by using the ctype_punct function - Returns TRUE if every character 
* 	in text is printable, but neither letter, digit or blank, FALSE otherwise (including empty strings).
* Requirements: PHP 5.2 or higher
*/
class Vm_Validate_Ctype_Punct extends Vm_Validator{

	/* 
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
?>