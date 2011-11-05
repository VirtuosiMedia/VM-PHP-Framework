<?php
/**
* @author Virtuosi Media Inc.
* @license: MIT License
* Description: A filter class that encrypts an input using the md5() function
* Requirements: PHP 5.2 or higher
*/
class Vm_Filter_Md5 extends Vm_Filter {

	/*
	 * @param string $input - The input to be filtered
	 * @return string - The filtered input
	 */
	public function filter($input){
		$this->value = $input;
		$input = trim($input);
		if (strlen($input) > 0){
			$input = md5($input);
		}
		$this->filteredValue = $input;
		return $input;
	}
}
?>
