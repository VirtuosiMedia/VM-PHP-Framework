<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description A  validator for 10 digit North American phone numbers - Evaluates TRUE if an empty string is passed. 
 * @note Area codes are required
 * @extends Vm\Validate\Regex
 * @namespace Vm\Validate
 */
namespace Vm\Validate;

class Phone extends \Vm\Validate\Regex {

	/** 
	 * @param string $input - The input to be validated
	 * @param string $error - optional - A custom error message to be returned if the input fails validation
	 */
	function __construct($input, $error = NULL){	
		$error = ($error) ? $error : 'Please enter a valid phone number including area code';
		parent::__construct($input, $error, '/^((([0-9]{1})*[- .(]*([0-9]{3})[- .)]*[0-9]{3}[- .]*[0-9]{4})+)*$/');
	}
}