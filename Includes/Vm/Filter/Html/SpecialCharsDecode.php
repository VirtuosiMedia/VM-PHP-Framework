<?php
/**
* @author Virtuosi Media Inc.
* @license: MIT License
* Description: A filter class that converts special HTML entities back to characters
* Requirements: PHP 5.2 or higher
*/
class Vm_Filter_Html_SpecialCharsDecode extends Vm_Filter {

	/*
	 * @param string $input - The input to be filtered
	 * @param const $quoteStyle - The style of single and double quotes - Defaults to ENT_COMPAT
	 * @return string - The filtered input
	 */
	public function filter($input, $quoteStyle = NULL){
		$this->value = $input;
		$input = htmlspecialchars_decode($input, $quoteStyle);
		$this->filteredValue = $input;
		return $input;
	}
}
?>