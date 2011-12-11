<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description A filter class that converts special HTML entities back to characters
 * @namspace Vm\Filter\Html
 */
namespace Vm\Filter\Html;

class SpecialCharsDecode extends \Vm\Filter {

	/**
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