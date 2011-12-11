<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description A validator for dates in Month-Day-Year format - Evaluates TRUE if an empty string is passed  
 * @note Vm\Validate\Date\Mdy only validates for the year for 1900-2099.
 * @extends Vm\Validate\Regex
 * @namespace Vm\Validate\Date
 */
namespace Vm\Validate\Date;

class Mdy extends \Vm\Validate\Regex {

	/**
	 * @param string $input - The input to be validated
	 * @param string $error - optional - A custom error message to be returned if the input fails validation
	 */
	function __construct($input, $error = NULL){	
		$error = ($error) ? $error : 'Please enter a valid date in M/D/Y format';
		parent::__construct($input, $error, '#^((0?[1-9]|1[012])[- /.](0?[1-9]|[12][0-9]|3[01])[- /.](19|20)?[0-9]{2})*$#');
	}
}