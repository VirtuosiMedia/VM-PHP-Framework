<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description A filter class that converts special characters to HTML entities
 * @namspace Vm\Filter\Html
 */
namespace Vm\Filter\Html;

class SpecialChars extends \Vm\Filter {

	/**
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