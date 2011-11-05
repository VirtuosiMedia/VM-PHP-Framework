<?php
/**
* @author Virtuosi Media Inc.
* @license: MIT License
* Description: A validator for string length by using the strlen function - Returns TRUE if $input is less than the
* 	maximum length, FALSE otherwise.
* Requirements: PHP 5.2 or higher
*/
class Vm_Validate_MaxLength extends Vm_Validator{

	/* 
	 * @param string $input - The input to be validated
	 * @param string $error - optional - A custom error message to be returned if the input fails validation
	 * @param int $maxLength - The maximum number of characters
	 */
	function __construct($input, $error = NULL, $maxLength){	
		$defaultError = "Please enter no more than $maxLength character(s)";
		if (strlen($input) > $maxLength){
			$this->error = ($error) ? $error : $defaultError;
			$this->validates = false;			
		}
	}
}
?>