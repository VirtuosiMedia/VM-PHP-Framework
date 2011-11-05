<?php
/**
* @author Virtuosi Media Inc.
* @license: MIT License
* Description: A filter class that strips all non-alphanumeric characters and hyphenates spaces
* Requirements: PHP 5.2 or higher
*/
class Vm_Filter_Hyphenate extends Vm_Filter {

	/*
	* @param string $input - The input to be filtered
	* @return string - The filtered input
	*/
	public function filter($input){
		$this->value = $input;
		$this->filteredValue = preg_replace(array('/[^\w\d\s]/', '/[\s]/'), array('', '-'), $input);
		return $this->filteredValue;
	}
}