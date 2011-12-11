<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description A validator for Discover credit card numbers - Evaluates TRUE if an empty string is passed. 
 * @extends Vm\Validate\Regex 
 * @namespace Vm\Validate\Credit
 */
namespace Vm\Validate\Credit;

class Discover extends \Vm\Validate\Regex {

	/** 
	 * @param string $input - The input to be validated
	 * @param string $error - optional - A custom error message to be returned if the input fails validation
	 */
	function __construct($input, $error = NULL){	
		$error = ($error) ? $error : 'Please enter a valid Discover credit card number';
		parent::__construct($input, $error, '/^(6011[0-9]{12}|622((12[6-9]|1[3-9][0-9])|([2-8][0-9][0-9])|(9(([0-1][0-9])|(2[0-5]))))[0-9]{10}|64[4-9][0-9]{13}|65[0-9]{14})*$/');
	}
}