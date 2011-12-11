<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description A validator for URLs - Evaluates TRUE if an empty string is passed
 * @extends Vm\Validate\Regex
 * @namespace Vm\Validate
 */
namespace Vm\Validate;

class Url extends \Vm\Validate\Regex {

	/** 
	 * @param string $input - The input to be validated
	 * @param string $error - optional - A custom error message to be returned if the input fails validation
	 */
	function __construct($input, $error = NULL){	
		$error = ($error) ? $error : 'Please enter a valid URL';
		parent::__construct($input, $error, '/^(((http|https|ftp):\/\/)?([[:alnum:]\-\.])+(\.)([[:alnum:]]){2,4}([[:alnum:]\/+=%&_\.~?\-]*))*$/');
	}
}