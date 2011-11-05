<?php
/**
* @author Virtuosi Media Inc.
* @license: MIT License
* Description: A validator for hexadecimal digits by using the ctype_xdigit function - Returns TRUE if every character in text 
*	is a hexadecimal 'digit', that is a decimal digit or a character from [A-Fa-f] , FALSE otherwise (including empty strings).
* Requirements: PHP 5.2 or higher
*/
class Vm_Validate_Ctype_Xdigit extends Vm_Validator{

	/* 
	 * @param string $input - The input to be validated
	 * @param string $error - optional - A custom error message to be returned if the input fails validation
	 */
	function __construct($input, $error = NULL){	
		$defaultError = 'This field must contain only hexidecimal digits';
		if (!ctype_xdigit($input)){
			$this->error = ($error) ? $error : $defaultError;
			$this->validates = false;			
		}
	}
}
?>