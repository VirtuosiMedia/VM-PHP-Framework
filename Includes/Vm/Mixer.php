<?php
/**
 * @author Virtuosi Media Inc.
 * @license MIT License
 * @description Vm_Mixer is an abstract class that simulates multiple inheritance using mixins. It allows you to extend 
 * 		the functionality of multiple classes and manage any conflicts between them, should they arise.
 */
abstract class Vm_Mixer {
	
	protected $methods = array();
	protected $mixins = array();
	protected $priorities = array();
	
	/**
	 * @description By adding a mixin, the class will automatically adopt all of a mixin's methods.
	 * @param object $mixin - The instantiated object that's methods should be adopted by the extending class.
	 */
	public function addMixin($mixin){
		$name = get_class($mixin);
		$this->mixins[$name] = $mixin;
		$methods = get_class_methods($name);
		$this->methods[$name] = $methods;
	}
	
	/**
	 * @description Gets the class's current mixins by name
	 * @return An array of mixin names
	 */
	public function getMixins(){
		return array_keys($this->methods);
	}

	/**
	 * @description Manages conflicts for the mixins.
	 * @param array $priorities - The method name as the key, the class name that has priority in a conflict as the value.
	 */
	public function setPriorities(array $priorities){
		$this->priorities = $priorities;
	}
	
	/**
	 * @description Magic method that calls the mixin methods automatically
	 * @param string $methodName - The name of the mixin method
	 * @param array $arguments - The arguments for the method
	 */
	public function __call($methodName, $arguments){
		foreach ($this->methods as $className=>$methods){
			if (in_array($methodName, $methods)){
				if ((in_array($methodName, array_keys($this->priorities)))&&($className == $this->priorities[$methodName])){
					call_user_func_array(array($className, $methodName), $arguments);
					break;
				} else if (!in_array($methodName, array_keys($this->priorities))){
					call_user_func_array(array($className, $methodName), $arguments);
					break;					
				}
			} 
		}
	}
}