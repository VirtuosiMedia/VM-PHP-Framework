<?php
/**
* @author Virtuosi Media Inc.
* @license: MIT License
* Description: A validator for control characters by using the ctype_cntrl function - Returns TRUE if every character in text 
*	is a control character from the current locale, FALSE otherwise (including empty strings).
* Requirements: PHP 5.2 or higher
*/
class Vm_Validate_Ctype_Cntrl extends Vm_Validator{

	/* 
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
?>