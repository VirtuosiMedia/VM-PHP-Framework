<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description A validator for UK Postal Codes - Evaluates TRUE if an empty string is passed
 * @extends Vm\Validate\Regex
 * @namespace Vm\Validate\Filter\Postal
 */
namespace Vm\Validate\Filter\Postal;

class Uk extends \Vm\Validate\Regex {

	/** 
	 * @param string $input - The input to be validated
	 * @param string $error - optional - A custom error message to be returned if the input fails validation
	 */
	function __construct($input, $error = NULL){	
		$error = ($error) ? $error : 'Please enter a valid UK Postal Code';
		parent::__construct($input, $error, '/^([A-Z]{1,2}[0-9][A-Z0-9]? [0-9][ABD-HJLNP-UW-Z]{2})*$/');
	}
}