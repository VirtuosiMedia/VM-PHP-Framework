<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description A filter class that URL-decodes a string
 * @namspace Vm\Filter
 */
namespace Vm\Filter;

class UrlDecode extends \Vm\Filter {

	/**
	 * @param string $input - The input to be filtered
	 * @return string - The filtered input
	 */
	public function filter($input){
		$this->value = $input;
		$input = urldecode($input);
		$this->filteredValue = $input;
		return $input;
	}
}