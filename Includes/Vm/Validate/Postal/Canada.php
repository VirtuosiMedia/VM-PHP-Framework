<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description A validator for Canadian Postal Codes - Evaluates TRUE if an empty string is passed
 * @extends Vm\Validate\Regex
 * @namespace Vm\Validate\Filter\Postal
 */
namespace Vm\Validate\Filter\Postal;

class Canada extends \Vm\Validate\Regex {

	/** 
	 * @param string $input - The input to be validated
	 * @param string $error - optional - A custom error message to be returned if the input fails validation
	 */
	function __construct($input, $error = NULL){	
		$error = ($error) ? $error : 'Please enter a valid Canadian Postal Code';
		parent::__construct($input, $error, '/^([ABCEGHJKLMNPRSTVXY][0-9][A-Z] [0-9][A-Z][0-9])*$/');
	}
}