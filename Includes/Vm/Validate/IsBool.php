<?php
/**
* @author Virtuosi Media Inc.
* @license: MIT License
* Description: A validator for booleans using the is_bool function - Returns TRUE if $input is a boolean, FALSE otherwise
* Requirements: PHP 5.2 or higher
*/
class Vm_Validate_IsBool extends Vm_Validator{

	/* 
	 * @param string $input - The input to be validated
	 * @param string $error - optional - A custom error message to be returned if the input fails validation
	 */
	function __construct($input, $error = NULL){	
		$defaultError = 'Value must be boolean';
		if (!is_bool($input)){
			$this->error = ($error) ? $error : $defaultError;
			$this->validates = false;			
		}
	}
}
?>