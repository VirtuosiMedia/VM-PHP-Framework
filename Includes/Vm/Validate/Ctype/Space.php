<?php
/**
* @author Virtuosi Media Inc.
* @license: MIT License
* Description: A validator for whitespace characters by using the ctype_space function - Returns TRUE if every character in text 
*	creates some sort of white space, FALSE otherwise (including empty strings). Besides the blank character this also includes tab, vertical tab, line feed, 
*	carriage return and form feed characters.
* Requirements: PHP 5.2 or higher
*/
class Vm_Validate_Ctype_Space extends Vm_Validator{

	/* 
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
?>