<?php
/**
* @author Virtuosi Media Inc.
* @license: MIT License
* Description: A validator for arrays using the is_array function - Returns TRUE if var is an array, 
*	FALSE otherwise.
* Requirements: PHP 5.2 or higher
*/
class Vm_Validate_Is_Array extends Vm_Validator{

	/* 
	 * @param string $input - The input to be validated
	 * @param string $error - optional - A custom error message to be returned if the input fails validation
	 */
	function __construct($input, $error = NULL){	
		$defaultError = 'Value must be an array';
		if (!is_array($input)){
			$this->error = ($error) ? $error : $defaultError;
			$this->validates = false;			
		}
	}
}
?>