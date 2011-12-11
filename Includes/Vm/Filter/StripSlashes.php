<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description A filter class that strips slashes from input
 * @namspace Vm\Filter
 */
namespace Vm\Filter;

class StripSlashes extends \Vm\Filter {

	/**
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