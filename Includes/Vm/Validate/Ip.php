<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description A validator for IP addresses - Evaluates TRUE if an empty string is passed. Note: matches 0.0.0.0 
 * 		through 255.255.255.255
 * @extends Vm\Validate\Regex
 * @namespace Vm\Validate
 */
namespace Vm\Validate;

class Ip extends \Vm\Validate\Regex {

	/**
	 * @param string $input - The input to be validated
	 * @param string $error - optional - A custom error message to be returned if the input fails validation
	 */
	function __construct($input, $error = NULL){	
		$error = ($error) ? $error : 'Please enter a valid IP address';
		parent::__construct($input, $error, '/^((?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?))*$/');
	}
}