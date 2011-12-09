<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description Creates an options array for a class, inspired by the MooTools JavaScript syntax for creating classes
 * @requirements PHP 5.2 or higher
 * @namespace Vm
 */
namespace Vm;

class Klass {
	
	// @var array $options - The generic options array for which extending classes should set default values
	protected $options = array();

	function __construct(){}
	
	/**
	 * @description The setOptions method should be called in the constructor of an extending class
	 * @param array $options - The options array resets any default options present in the class
	 * @param array $newDefaultOptions - optional - An array of any new default options to add to the options array
	 * @return - $this
	 */
	public function setOptions($options, $newDefaultOptions = NULL) {
		if (($newDefaultOptions) && (is_array($newDefaultOptions))){
			foreach ($newDefaultOptions as $key => $value){
				$this->options[$key] = $value;
			}					
		}
		if (is_array($options)){
			foreach ($options as $key => $value){
				$this->options[$key] = $value;
			}
		}
		return $this;
	}

	/**
	 * @description Gets the options array
	 * @return array - The options array
	 */
	public function getOptions(){
		return $this->options;
	}

	/**
	 * @description Gets the options value
	 * @param string $key - The option key to return a value for
	 * @return array - The option key's value
	 */	
	public function getValue($key){
		return $this->options[$key];
	}
	
	/**
	 * @param string $value - The value of the key
	 * @return - The key for the given value if it exists, FALSE otherwise
	 */
	public function getKey($value){
		$key = array_keys($this->options, $value);		
		return (sizeof($key) > 0) ? $key[0] : FALSE;
	}
}