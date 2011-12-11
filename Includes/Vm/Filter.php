<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description An abstract generic filter class to be extended by specific filters
 * @namespace Vm
 */
namespace Vm;

abstract class Filter extends \Vm\Klass {

	protected $filteredValue = NULL;
	protected $value = NULL;
	
	/**
	 * @see Vm/Vm_Klass#getValue($key)
	 * @return string - The original value of the input before it has been filtered
	 */	
	public function getValue() {
		return $this->value;
	}
	
	/**
	 * @return string - The filtered value of the input
	 */	
	public function getFilteredValue(){
		return $this->filteredValue;
	}
}