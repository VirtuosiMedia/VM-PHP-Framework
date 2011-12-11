<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description Creates a class for housing a single language translation, meant for use with Vm_Translate
 * @namespace Vm
 */
namespace Vm;

class Lang extends \Vm\Klass {
	
	/**
	 * @description Appends an array of language pairs to the parent language file if one exists, else to it's own 
	 * 		storage array
	 * @param array $langItems - An array of language pairs with the identifier as the key, the translation as the value 
	 * @return The object, for chaining
	 */
	public function append(array $langItems){
		$this->setOptions(array(), $langItems);
		return $this;
	}
}