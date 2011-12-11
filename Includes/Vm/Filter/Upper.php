<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description A filter class that turns a string to uppercase
 * @namspace Vm\Filter
 */
namespace Vm\Filter;

class Upper extends \Vm\Filter {

	/**
	 * @param string $input - The input to be filtered
	 * @return string - The filtered input
	 */
	public function filter($input){
		$this->value = $input;
		$input = strtoupper($input);
		$this->filteredValue = $input;
		return $input;
	}
}