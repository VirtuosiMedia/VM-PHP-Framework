<?php
/**
* @author Virtuosi Media Inc.
* @license: MIT License
* Description: A validator for alphanumeric characters by using the ctype_alnum function - Returns TRUE if every character in text 
*	is either a letter or a digit, FALSE otherwise (including empty strings).
* Requirements: PHP 5.2 or higher
*/
class Vm_Validate_Ctype_Alnum extends Vm_Validator{

	/* 
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
?>