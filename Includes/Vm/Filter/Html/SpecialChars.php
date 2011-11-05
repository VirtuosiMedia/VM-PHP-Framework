<?php
/**
* @author Virtuosi Media Inc.
* @license: MIT License
* Description: A filter class that converts special characters to HTML entities
* Requirements: PHP 5.2 or higher
*/
class Vm_Filter_Html_SpecialChars extends Vm_Filter {

	/*
	 * @param string $input - The input to be filtered
	 * @param const $quoteStyle - The style of single and double quotes - Defaults to ENT_COMPAT
	 * @param string $charset - The character set to be used - Defaults to 'ISO-8859-1'	
	 * @return string - The filtered input
	 */
	public function filter($input, $quoteStyle = NULL, $charset = NULL){
		$this->value = $input;
		$input = htmlspecialchars($input, $quoteStyle, $charset);
		$this->filteredValue = $input;
		return $input;
	}
}
?>