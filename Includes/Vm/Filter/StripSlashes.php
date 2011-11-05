<?php
/**
* @author Virtuosi Media Inc.
* @license: MIT License
* Description: A filter class that strips slashes from input
* Requirements: PHP 5.2 or higher
*/
class Vm_Filter_StripSlashes extends Vm_Filter {

	/*
	 * @param string $input - The input to be filtered
	 * @return string - The filtered input
	 */
	public function filter($input){
		$this->value = $input;
		$input = stripslashes($input);
		$this->filteredValue = $input;
		return $input;
	}
}
?>
