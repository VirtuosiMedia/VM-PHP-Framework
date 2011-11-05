<?php
/**
* @author Virtuosi Media Inc.
* @license: MIT License
* Description: A generic filter class to be extended by specific filters
* Requirements: PHP 5.2 or higher
*/
class Vm_Filter extends Vm_Klass {

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
?>