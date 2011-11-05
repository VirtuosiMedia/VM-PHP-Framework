<?php
/**
* @author Virtuosi Media Inc.
* @license: MIT License
* Description: A generic regular expression validator, to be extended with specific regex filters 
* Requirements: PHP 5.2 or higher
*/
class Vm_Validate_Regex extends Vm_Validator{

	/* 
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
?>