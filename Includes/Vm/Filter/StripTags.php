<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description A filter class that strips slashes from input
 * @namespace Vm\Filter
 */
namespace Vm\Filter;

class StripTags extends \Vm\Filter {

	/**
	 * @param mixed $input - The input to be filtered
	 * @param string $tagWhiteList - The allowable tags that won't be stripped, ie: '<b><i><p>'
	 * @return string - The filtered input
	 */
	public function filter($input, $tagWhiteList = NULL){
		$this->value = $input;
		if (is_array($input)){
			$size = sizeof($input);
			$this->filteredValue = array();
			for($i=0; $i < $size; $i++){
				$this->filteredValue[$i] = strip_tags($input[$i], $tagWhiteList);
			}	
		} else {		
			$this->filteredValue = strip_tags($input, $tagWhiteList);
		}
		return $this->filteredValue;
	}
}