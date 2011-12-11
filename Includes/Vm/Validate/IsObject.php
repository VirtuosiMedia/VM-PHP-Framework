<?php
/**
* @author Virtuosi Media Inc.
* @license: MIT License
* Description: A validator for objects using the is_object function - Returns TRUE if $input is an object, FALSE otherwise.
* Requirements: PHP 5.2 or higher
*/
class Vm_Validate_IsObject extends Vm_Validator{

	/* 
	 * @param string $input - The input to be validated
	 * @param string $error - optional - A custom error message to be returned if the input fails validation
	 */
	function __construct($input, $error = NULL){	
		$defaultError = 'Value must be an object';
		if (!is_object($input)){
			$this->error = ($error) ? $error : $defaultError;
			$this->validates = false;			
		}
	}
}
?>