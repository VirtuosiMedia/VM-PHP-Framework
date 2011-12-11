<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description A validator for alphanumeric characters - Evaluates TRUE if an empty string is passed
 * @extends Vm\Validate\Regex
 * @namespace Vm\Validate
 */
namespace Vm\Validate;

class Alnum extends \Vm\Validate\Regex {

	/**
	 * @param string $input - The input to be validated
	 * @param string $error - optional - A custom error message to be returned if the input fails validation
	 */
	function __construct($input, $error = NULL){	
		$error = ($error) ? $error : 'This field may only contain letters or numbers';
		parent::__construct($input, $error, '/^[a-zA-Z0-9]*$/');
	}
}