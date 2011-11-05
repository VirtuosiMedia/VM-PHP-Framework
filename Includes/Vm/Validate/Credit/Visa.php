<?php
/**
* @author Virtuosi Media Inc.
* @license: MIT License
* Description: A validator for Visa credit card numbers - Evaluates TRUE if an empty string is passed. 
* Requirements: PHP 5.2 or higher
*/
class Vm_Validate_Credit_Visa extends Vm_Validate_Regex{

	/* 
	 * @param string $input - The input to be validated
	 * @param string $error - optional - A custom error message to be returned if the input fails validation
	 */
	function __construct($input, $error = NULL){	
		$error = ($error) ? $error : 'Please enter a valid Visa credit card number';
		parent::__construct($input, $error, '/^(4[0-9]{12}(?:[0-9]{3})?)*$/');
	}
}
?>