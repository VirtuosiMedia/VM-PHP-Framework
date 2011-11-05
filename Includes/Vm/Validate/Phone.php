<?php
/**
* @author Virtuosi Media Inc.
* @license: MIT License
* Description: A  validator for 10 digit North American phone numbers - Evaluates TRUE if an empty string is passed. 
*	Note: Area codes are required
* Requirements: PHP 5.2 or higher
*/
class Vm_Validate_Phone extends Vm_Validate_Regex{

	/* 
	 * @param string $input - The input to be validated
	 * @param string $error - optional - A custom error message to be returned if the input fails validation
	 */
	function __construct($input, $error = NULL){	
		$error = ($error) ? $error : 'Please enter a valid phone number including area code';
		parent::__construct($input, $error, '/^((([0-9]{1})*[- .(]*([0-9]{3})[- .)]*[0-9]{3}[- .]*[0-9]{4})+)*$/');
	}
}
?>
