<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description A filter class that turns a string to lowercase
 * @namspace Vm\Filter
 */
namespace Vm\Filter;

class Lower extends \Vm\Filter {

	/**
	 * @param string $input - The input to be filtered
	 * @return string - The filtered input
	 */
	public function filter($input){
		$this->value = $input;
		$this->filteredValue = strtolower($input);
		return $this->filteredValue;
	}
}