<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description A generic regular expression validator, to be extended with specific regex filters 
 * @extends Vm\Validator
 * @namespace Vm\Validate
 */
namespace Vm\Validate;

class Regex extends \Vm\Validator {

	/**
	 * @param string $input - The input to be validated
	 * @param string $error - A custom error message to be returned if the input fails validation
	 * @param string $regex - The regular expression to be evaluated 
	 */
	function __construct($input, $error, $regex){	
		if (!preg_match($regex, $input)){
			$this->setError($error);			
		}
	}
}