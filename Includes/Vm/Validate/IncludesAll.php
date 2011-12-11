<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description A validator for determining if the input includes at least one item in an array by using the in_array 
 * 		function. Returns TRUE if $input contains at least one item in $includesArray, FALSE otherwise.
 * @extends Vm\Validator
 * @namespace Vm\Validate
 */
namespace Vm\Validate;

class IncludesAll extends \Vm\Validator {

	/** 
	 * @param string $input - The input to be validated
	 * @param string $error - A custom error message to be returned if the input fails validation
	 * @param int $includesArray - An array of items to be checked for
	 */
	function __construct($input, $error, array $includesArray){	
		$defaultError = "This field did not contain all of the following values: ".implode(', ', $includesArray);
		$included = true;
		foreach($includesArray as $check){
			if (!preg_match('#'.$check.'#', $input)){
				$included = false;
			}
		}
		if (!$included){
			$this->error = (!empty($error)) ? $error : $defaultError;
			$this->validates = false;			
		}
	}
}