<?php
/**
* @author Virtuosi Media Inc.
* @license: MIT License
* Description: A validator for determining if the input excludes all items in an array by using the in_array function 
* 	Returns TRUE if $input contains at least one item in $includesArray, FALSE otherwise.
* Requirements: PHP 5.2 or higher
*/
class Vm_Validate_Excludes extends Vm_Validator{

	/* 
	 * @param string $input - The input to be validated
	 * @param string $error - A custom error message to be returned if the input fails validation
	 * @param int $excludesArray - An array of items to be checked for 
	 */
	function __construct($input, $error, array $excludesArray){	
		$defaultError = "This field cannot contain any of the following values: ".implode(', ', $excludesArray);
		$excluded = true;
		foreach($excludesArray as $check){
			if (preg_match('#'.$check.'#', $input)){
				$excluded = false;
			}
		}
		if (!$excluded){
			$this->error = (!empty($error)) ? $error : $defaultError;
			$this->validates = false;			
		}
	}
}
?>