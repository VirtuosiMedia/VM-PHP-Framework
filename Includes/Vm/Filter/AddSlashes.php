<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description A filter class that adds slashes to input
 * @requirements PHP 5.2 or higher
 * @namspace Vm\Filter
 */
namespace Vm\Filter;

class AddSlashes extends \Vm\Filter {

	/**
	 * @param string $input - The input to be filtered
	 * @return string - The filtered input
	 */
	public function filter($input){
		$this->value = $input;
		$this->filteredValue = addslashes($input);
		return $this->filteredValue;
	}
}