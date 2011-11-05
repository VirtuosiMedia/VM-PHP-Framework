<?php
/**
* @author Virtuosi Media Inc.
* @license: MIT License
* Description: A validator for required fields - Returns TRUE if minimum length of 1 is met, FALSE otherwise. 
*	Note: It will automatically trim the input of whitespace
* Requirements: PHP 5.2 or higher
*/
class Vm_Validate_Required extends Vm_Validator{

	/* 
	 * @param int $minLength - The minimum number of characters	
	 * @param mixed $input - The input to be validated
	 * @param string $error - optional - A custom error message to be returned if the input fails validation
	 */
	function __construct($input, $error = NULL){	
		$defaultError = "This field is required";
		if (is_array($input)){
			$errorExists = (sizeof($input) < 1) ? TRUE : FALSE;	
		} else {
			$input = trim($input);	
			$errorExists = (strlen($input) < 1) ? TRUE : FALSE;
		}
		
		if ($errorExists){
			$this->error = ($error) ? $error : $defaultError;
			$this->validates = false;			
		}		
	}
}
?>