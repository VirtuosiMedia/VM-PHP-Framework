<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description A validator for Visa credit card numbers - Evaluates TRUE if an empty string is passed. 
 * @extends Vm\Validate\Regex 
 * @namespace Vm\Validate\Credit
 */
namespace Vm\Validate\Credit;

class Visa extends \Vm\Validate\Regex {

	/** 
	 * @param string $input - The input to be validated
	 * @param string $error - optional - A custom error message to be returned if the input fails validation
	 */
	function __construct($input, $error = NULL){	
		$error = ($error) ? $error : 'Please enter a valid Visa credit card number';
		parent::__construct($input, $error, '/^(4[0-9]{12}(?:[0-9]{3})?)*$/');
	}
}