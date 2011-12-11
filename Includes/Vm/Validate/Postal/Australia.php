<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description A validator for Australian Postal Codes - Evaluates TRUE if an empty string is passed
 * @extends Vm\Validate\Regex
 * @namespace Vm\Validate\Filter\Postal
 */
namespace Vm\Validate\Filter\Postal;

class Australia extends \Vm\Validate\Regex {

	/** 
	 * @param string $input - The input to be validated
	 * @param string $error - optional - A custom error message to be returned if the input fails validation
	 */
	function __construct($input, $error = NULL){	
		$error = ($error) ? $error : 'Please enter a valid Australian Postal Code';
		parent::__construct($input, $error, '/^((0[289][0-9]{2})|([1345689][0-9]{3})|(2[0-8][0-9]{2})|(290[0-9])|(291[0-4])|(7[0-4][0-9]{2})|(7[8-9][0-9]{2}))*$/');
	}
}