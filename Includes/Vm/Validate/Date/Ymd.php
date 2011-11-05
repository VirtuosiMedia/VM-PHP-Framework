<?php
/**
* @author Virtuosi Media Inc.
* @license: MIT License
* Description: A validator for dates in YMD format - Evaluates TRUE if an empty string is passed. 
*	Note: Only validates for the year for 1900-2099
* Requirements: PHP 5.2 or higher
*/
class Vm_Validate_Date_Ymd extends Vm_Validate_Regex{

	/* 
	 * @param string $input - The input to be validated
	 * @param string $error - optional - A custom error message to be returned if the input fails validation
	 */
	function __construct($input, $error = NULL){	
		$error = ($error) ? $error : 'Please enter a valid date in Y/M/D format';
		parent::__construct($input, $error, '#^((19|20)?[0-9]{2}[- /.](0?[1-9]|1[012])[- /.](0?[1-9]|[12][0-9]|3[01]))*$#');
	}
}
?>
