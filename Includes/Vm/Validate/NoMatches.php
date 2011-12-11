<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description A validator for determining if one input is not equal to another - Returns TRUE if values do not match, 
 *		FALSE otherwise.
 * @extends Vm\Validator
 * @namespace Vm\Validate
 */
namespace Vm\Validate;

class NoMatches extends \Vm\Validator {

	/** 
	 * @param string $input - The input to be validated
	 * @param string $error - A custom error message to be returned if the input fails validation
	 * @param string $comparisonInput - The input against which $input should be compared. Note: This parameter is not
	 * 		optional, but it is given a null value to prevent empty strings for the comparisonInput from triggering a 
	 * 		PHP error. An empty string or NULL value can still trigger a validation error.	
	 */
	function __construct($input, $error, $comparisonInput = NULL){	
		$defaultError = 'Values cannot match';
		if ($input == $comparisonInput){
			$this->error = (!empty($error)) ? $error : $defaultError;
			$this->validates = false;			
		}
	}
}