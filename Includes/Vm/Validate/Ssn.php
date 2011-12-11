<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description A validator for Social Security Numbers - Evaluates TRUE if an empty string is passed. 
 * @extends Vm\Validate\Regex
 * @namespace Vm\Validate
 */
namespace Vm\Validate;

class Ssn extends \Vm\Validate\Regex {

	/** 
	 * @param string $input - The input to be validated
	 * @param string $error - optional - A custom error message to be returned if the input fails validation
	 */
	function __construct($input, $error = NULL){	
		$error = ($error) ? $error : 'Please enter a valid Social Security Number';
		parent::__construct($input, $error, '/^([0-9]{3}[-]*[0-9]{2}[-]*[0-9]{4})*$/');
	}
}