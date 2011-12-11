<?php
/**
* @author Virtuosi Media Inc.
* @license: MIT License
* Description: A validator for integers using the is_int function - Returns TRUE if var is an integer, 
*	FALSE otherwise (including empty or numeric strings).
* Requirements: PHP 5.2 or higher
*/
class Vm_Validate_IsInt extends Vm_Validator{

	/* 
	 * @param string $input - The input to be validated
	 * @param string $error - optional - A custom error message to be returned if the input fails validation
	 */
	function __construct($input, $error = NULL){	
		$defaultError = 'Value must be an integer';
		if (!is_int($input)){
			$this->error = ($error) ? $error : $defaultError;
			$this->validates = false;			
		}
	}
}
?>