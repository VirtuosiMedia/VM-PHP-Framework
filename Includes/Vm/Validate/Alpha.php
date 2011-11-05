<?php
/**
* @author Virtuosi Media Inc.
* @license: MIT License
* Description: A validator for alphabetic characters - Evaluates TRUE if an empty string is passed
* Requirements: PHP 5.2 or higher
*/
class Vm_Validate_Alpha extends Vm_Validate_Regex{

	/* 
	 * @param string $input - The input to be validated
	 * @param string $error - optional - A custom error message to be returned if the input fails validation
	 */
	function __construct($input, $error = NULL){	
		$error = ($error) ? $error : 'This field may only contain letters';
		parent::__construct($input, $error, '/^[a-zA-Z]*$/');
	}
}
?>