<?php
/**
* @author Virtuosi Media Inc.
* @license: MIT License
* Description: A validator for Australian Postal Codes - Evaluates TRUE if an empty string is passed
* Requirements: PHP 5.2 or higher
*/
class Vm_Validate_Filter_Postal_Australia extends Vm_Validate_Regex{

	/* 
	 * @param string $input - The input to be validated
	 * @param string $error - optional - A custom error message to be returned if the input fails validation
	 */
	function __construct($input, $error = NULL){	
		$error = ($error) ? $error : 'Please enter a valid Australian Postal Code';
		parent::__construct($input, $error, '/^((0[289][0-9]{2})|([1345689][0-9]{3})|(2[0-8][0-9]{2})|(290[0-9])|(291[0-4])|(7[0-4][0-9]{2})|(7[8-9][0-9]{2}))*$/');
	}
}
?>