<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description A filter class that trims the input of whitespace
 * @namspace Vm\Filter
 */
namespace Vm\Filter;

class Trim extends \Vm\Filter {

	/**
	 * @param string $input - The input to be filtered
	 * @return string - The filtered input
	 */
	public function filter($input){
		$this->value = $input;
		if (is_array($input)){
			$size = sizeof($input);
			for($i=0; $i < $size; $i++){
				$input[$i] = trim($input[$i]);
			}	
		} else {
			$input = trim($input);
		}
		$this->filteredValue = $input;
		return $input;
	}
}