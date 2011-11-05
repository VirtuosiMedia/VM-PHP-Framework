<?php
/**
* @author Virtuosi Media Inc.
* @license: MIT License
* Description: A filter class that turns a string to uppercase
* Requirements: PHP 5.2 or higher
*/
class Vm_Filter_Upper extends Vm_Filter {

	/*
	 * @param string $input - The input to be filtered
	 * @return string - The filtered input
	 */
	public function filter($input){
		$this->value = $input;
		$input = strtoupper($input);
		$this->filteredValue = $input;
		return $input;
	}
}
?>