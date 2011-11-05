<?php
/**
* @author Virtuosi Media Inc.
* @license: MIT License
* Description: A validator for determining if one input is equal to another - Returns TRUE if values match, 
*	FALSE otherwise.
* Requirements: PHP 5.2 or higher
*/
class Vm_Validate_Matches extends Vm_Validator{

	/* 
	 * @param string $input - The input to be validated
	 * @param string $error - A custom error message to be returned if the input fails validation
	 * @param string $comparisonInput - The input against which $input should be compared. Note: This parameter is not
	 * 	optional, but it is given a null value to prevent empty strings for the comparisonInput from triggering a PHP error.
	 * 	An empty string or NULL value will still trigger a validation error.
	 */
	function __construct($input, $error, $comparisonInput = NULL){	
		$defaultError = 'Values do not match';
		if ($input != $comparisonInput){
			$this->error = (!empty($error)) ? $error : $defaultError;
			$this->validates = false;			
		}
	}
}
?>