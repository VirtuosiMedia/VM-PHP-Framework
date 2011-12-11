<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description A validator for email addresses - Evaluates TRUE if an empty string is passed. 
 * @note Vm\Validate\Email validates most email addresses, but it is not completely RFC compliant.
 * @extends Vm\Validate\Regex
 * @namespace Vm\Validate
 */
namespace Vm\Validate;

class Email extends \Vm\Validate\Regex {

	/** 
	 * @param string $input - The input to be validated
	 * @param string $error - optional - A custom error message to be returned if the input fails validation
	 */
	function __construct($input, $error = NULL){	
		$error = ($error) ? $error : 'Please enter a valid email address';
		parent::__construct($input, $error, '/^([a-zA-Z0-9._%-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4})*$/');
	}
}