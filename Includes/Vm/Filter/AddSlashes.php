<?php
/**
* @author Virtuosi Media Inc.
* @license: MIT License
* Description: A filter class that adds slashes to input
* Requirements: PHP 5.2 or higher
*/
class Vm_Filter_AddSlashes extends Vm_Filter {

	/*
	 * @param string $input - The input to be filtered
	 * @return string - The filtered input
	 */
	public function filter($input){
		$this->value = $input;
		$this->filteredValue = addslashes($input);
		return $this->filteredValue;
	}
}
?>
