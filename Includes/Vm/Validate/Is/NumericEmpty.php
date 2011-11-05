<?php
/**
* @author Virtuosi Media Inc.
* @license: MIT License
* Description: A validator for numeric values or strings using the is_numeric function - Returns TRUE if $input 
*	is a number or a numeric string, FALSE otherwise (including empty strings).
* Requirements: PHP 5.2 or higher
*/
class Vm_Validate_Is_NumericEmpty extends Vm_Validator{

	/* 
	 * @param string $input - The input to be validated
	 * @param string $error - optional - A custom error message to be returned if the input fails validation
	 */
	function __construct($input, $error = NULL){	
		$defaultError = 'Value must be numeric';
		if (!is_numeric($input)){
			$this->error = ($error) ? $error : $defaultError;
			$this->validates = false;			
		}
	}
}
?>