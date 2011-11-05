<?php
/**
* @author Virtuosi Media Inc.
* @license: MIT License
* Description: A validator for Canadian provinces abbreviations - Evaluates TRUE if an empty string is passed. 
*	Note: The abbreviation must be capitalized
* Requirements: PHP 5.2 or higher
*/
class Vm_Validate_CanadaProvince extends Vm_Validate_Regex{

	/* 
	 * @param string $input - The input to be validated
	 * @param string $error - optional - A custom error message to be returned if the input fails validation
	 */
	function __construct($input, $error = NULL){	
		$error = ($error) ? $error : 'Please enter a valid 2-letter Canadian province abbreviation';
		parent::__construct($input, $error, '/^(?:AB|BC|MB|N[BLTSU]|ON|PE|QC|SK|YT)*$/');
	}
}
?>
