<?php
/**
* @author Virtuosi Media Inc.
* @license: MIT License
* Description: A validator for null values using the is_null function - Returns TRUE if var is NULL, 
*	FALSE otherwise (including empty strings).
* Requirements: PHP 5.2 or higher
*/
class Vm_Validate_Is_Null extends Vm_Validator{

	/* 
	 * @param string $input - The input to be validated
	 * @param string $error - optional - A custom error message to be returned if the input fails validation
	 */
	function __construct($input, $error = NULL){	
		$defaultError = 'Value must be NULL';
		if (!is_null($input)){
			$this->error = ($error) ? $error : $defaultError;
			$this->validates = false;			
		}
	}
}
?>