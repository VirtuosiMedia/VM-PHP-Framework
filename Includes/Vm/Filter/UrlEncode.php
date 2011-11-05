<?php
/**
* @author Virtuosi Media Inc.
* @license: MIT License
* Description: A filter class that URL-encodes a string
* Requirements: PHP 5.2 or higher
*/
class Vm_Filter_UrlEncode extends Vm_Filter {

	/*
	 * @param string $input - The input to be filtered
	 * @return string - The filtered input
	 */
	public function filter($input){
		$this->value = $input;
		$input = urlencode($input);
		$this->filteredValue = $input;
		return $input;
	}
}
?>