<?php
/**
* @author Virtuosi Media Inc.
* @license: MIT License
* Description: A validator for passwords - Evaluates FALSE if an empty string is passed
* Requirements: PHP 5.2 or higher
*/
class Vm_Validate_Password extends Vm_Validate_Regex{

	/* 
	 * @param string $input - The input to be validated
	 * @param string $error - optional - A custom error message to be returned if the input fails validation
	 */
	function __construct($input, $error = NULL){	
		$error = ($error) ? $error : 'Your password must contain one lowercase letter, one uppercase letter, one number, and be at least 6 characters long';
		parent::__construct($input, $error, '/^(?=^.{6,}$)((?=.*[A-Za-z0-9])(?=.*[A-Z])(?=.*[a-z]))^.*$/');
	}
}
?>
