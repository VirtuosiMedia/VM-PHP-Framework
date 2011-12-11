<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description A filter class that strips all non-alphanumeric characters and hyphenates spaces
 * @namspace Vm\Filter
 */
namespace Vm\Filter;

class Hyphenate extends \Vm\Filter {

	/**
	 * @param string $input - The input to be filtered
	 * @return string - The filtered input
	 */
	public function filter($input){
		$this->value = $input;
		$this->filteredValue = preg_replace(array('/[^\w\d\s]/', '/[\s]/'), array('', '-'), $input);
		return $this->filteredValue;
	}
}