<?php
/**
* @author Virtuosi Media Inc.
* @license: MIT License
* Description: A validator for string length by using the strlen function - Returns TRUE if minimum length is met, FALSE otherwise.
* Requirements: PHP 5.2 or higher
*/
class Vm_Validate_MinLength extends Vm_Validator{

	/**
	 * @param string $input - The input to be validated
	 * @param string $error - A custom error message to be returned if the input fails validation
	 * @param int $minLength - The minimum number of characters
	 */
	function __construct($input, $error, $minLength){	
		$defaultError = "Please enter at least $minLength character(s)";	
		if (strlen($input) < $minLength){
			$this->error = (!empty($error)) ? $error : $defaultError;
			$this->validates = false;			
		}
	}
}
?>