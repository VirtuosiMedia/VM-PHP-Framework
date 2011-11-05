<?php
/**
* @author Virtuosi Media Inc.
* @license: MIT License
* Description: A validator for email addresses - Evaluates TRUE if an empty string is passed. Note: it is not completely RFC compliant
* Requirements: PHP 5.2 or higher
*/
class Vm_Validate_Email extends Vm_Validate_Regex{

	/* 
	 * @param string $input - The input to be validated
	 * @param string $error - optional - A custom error message to be returned if the input fails validation
	 */
	function __construct($input, $error = NULL){	
		$error = ($error) ? $error : 'Please enter a valid email address';
		parent::__construct($input, $error, '/^([a-zA-Z0-9._%-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4})*$/');
	}
}
?>
