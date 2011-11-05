<?php
/**
* @author Virtuosi Media
* @author Virtuosi Media Inc.
* @license: MIT License
* Description: A validator for printable characters by using the ctype_print function - Returns TRUE if every 
* 	character in text  will actually create output (including blanks). Returns FALSE if text contains control characters or 
*	characters that do not have any output or control function at all (including empty strings).
* Requirements: PHP 5.2 or higher
*/
class Vm_Validate_Ctype_Print extends Vm_Validator{

	/* 
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
?>