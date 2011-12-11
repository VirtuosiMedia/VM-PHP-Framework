<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description A validator for Canadian provinces abbreviations - Evaluates TRUE if an empty string is passed. 
 * @note The abbreviation must be capitalized
 * @extends Vm\Validate\Regex
 * @namespace Vm\Validate
 */
namespace Vm\Validate;

class CanadaProvince extends \Vm\Validate\Regex {

	/** 
	 * @param string $input - The input to be validated
	 * @param string $error - optional - A custom error message to be returned if the input fails validation
	 */
	function __construct($input, $error = NULL){	
		$error = ($error) ? $error : 'Please enter a valid 2-letter Canadian province abbreviation';
		parent::__construct($input, $error, '/^(?:AB|BC|MB|N[BLTSU]|ON|PE|QC|SK|YT)*$/');
	}
}