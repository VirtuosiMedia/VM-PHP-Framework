<?php
/**
* @author Virtuosi Media Inc.
* @license: MIT License
* Description: A filter class that salts an input with a defined constant called SALT
* Requirements: PHP 5.2 or higher
*/
class Vm_Filter_Salt extends Vm_Filter {

	/*
	 * @param string $input - The input to be filtered
	 * @param string $salt - The salt to combine with the input
	 * @return string - The filtered input
	 */
	public function filter($input, $salt){
		$this->value = $input;
		$input = trim($input);
		if (strlen($input) > 0){
			$input = $salt.$input;
		}
		$this->filteredValue = $input;
		return $input;
	}
}
?>