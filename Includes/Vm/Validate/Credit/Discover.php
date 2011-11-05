<?php
/**
* @author Virtuosi Media Inc.
* @license: MIT License
* Description: A validator for Discover credit card numbers - Evaluates TRUE if an empty string is passed. 
* Requirements: PHP 5.2 or higher
*/
class Vm_Validate_Credit_Discover extends Vm_Validate_Regex{

	/* 
	 * @param string $input - The input to be validated
	 * @param string $error - optional - A custom error message to be returned if the input fails validation
	 */
	function __construct($input, $error = NULL){	
		$error = ($error) ? $error : 'Please enter a valid Discover credit card number';
		parent::__construct($input, $error, '/^(6011[0-9]{12}|622((12[6-9]|1[3-9][0-9])|([2-8][0-9][0-9])|(9(([0-1][0-9])|(2[0-5]))))[0-9]{10}|64[4-9][0-9]{13}|65[0-9]{14})*$/');
	}
}
?>