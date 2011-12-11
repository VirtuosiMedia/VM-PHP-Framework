<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description A validator for alphabetic characters and spaces - Evaluates TRUE if an empty string is passed
 * @extends Vm\Validate\Regex
 * @namespace Vm\Validate
 */
namespace Vm\Validate;

class AlphaSpace extends \Vm\Validate\Regex {

	/** 
	 * @param string $input - The input to be validated
	 * @param string $error - optional - A custom error message to be returned if the input fails validation
	 */
	function __construct($input, $error = NULL){	
		$error = ($error) ? $error : 'This field may only contain letters or spaces';
		parent::__construct($input, $error, '/^[a-zA-Z ]*$/');
	}
}