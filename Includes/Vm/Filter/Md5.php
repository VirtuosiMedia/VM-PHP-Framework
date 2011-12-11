<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description A filter class that encrypts an input using the md5() function
 * @namspace Vm\Filter
 */
namespace Vm\Filter;

class Md5 extends \Vm\Filter {

	/**
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