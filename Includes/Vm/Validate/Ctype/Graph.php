<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description A validator for visibly printable characters by using the ctype_graph function -Returns TRUE if every 
 * 		character in text is printable and actually creates visible output (no white space), FALSE otherwise (including 
 * 		empty strings).
 * @extends Vm\Validator
 * @namespace Vm\Validate\Ctype
 */
namespace Vm\Validate\Ctype;

class Graph extends \Vm\Validator {

	/** 
	 * @param string $input - The input to be validated
	 * @param string $error - optional - A custom error message to be returned if the input fails validation
	 */
	function __construct($input, $error = NULL){	
		$defaultError = 'This field must contain only visibly printable characters';
		if (!ctype_graph($input)){
			$this->error = ($error) ? $error : $defaultError;
			$this->validates = false;			
		}
	}
}